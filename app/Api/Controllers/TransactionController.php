<?php

namespace App\Api\Controllers;

#require 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Transaction;
use App\Model\Book;
use App\User;
use Illuminate\Support\Facades\DB;
use Moip\Moip;
use Moip\Auth\BasicAuth;
use \Datetime;


class TransactionController extends Controller
{

    public function index(Request $request)
    {
        $message = '';
        if ($request->has('flag') && $request->get('flag') == 'payment') {
            return $this->makePayments($request);
        }
        $buyerId = $request->get('user_id');
        $sellerId = $request->get('user_id');
        if($transactions = DB::table('transactions')
                ->where('buyer_id', $buyerId)
                ->orwhere('seller_id', $sellerId)
                ->get())
            {
            if($transactions->isEmpty())
                $message = 'empty';
            else
                $message = 'success';
            }
        else
            $message = 'error';
        return response()->json(array('message' => $message, 'data' => $transactions->toArray()));
    }

    public function store(Request $request)
    {

        if ($request->get('flag') == 'payment') {
          return $this->makePayments($request);
        }

        $TRANSACTION_STATUS_BUYED = 'BUYED';
        $TRANSACTION_STATUS_RENTED = 'RENTED';

        $booksIds = $request->get('book_id');
//        return $booksIds[1];
        $buyerId = $request->get('buyer_id');
        $sellerIds = $request->get('seller_id');
        $isBuy = $request->get('is_buy');
        if($isBuy)
            $isBuy = 1;
        else
            $isBuy = 0;
        $shippingAddressId = $request->get('shipping_address_id');
        $billingAddressId = $request->get('billing_address_id');
        $cardLastDigits = $request->get('card_last_digits');
        // $paidValue = $request->get('paid_value');
        $payMethod = $request->get('payment_method');
        $rentDur = $request->get('rent_duration');
        $status = $request->get('status');

        if(!$rentDur)
            $rentDur = 0;

        $messages[0] = 'error';
        $transactions[0] = new Transaction();

        for($i = 0; $i < count($booksIds); $i++)
        {
            $book = DB::table('books')->where('id', $booksIds[$i])->first();
            $transaction = new Transaction();
            $transaction->buyer_id = $buyerId;
            $transaction->is_buy = $isBuy;
            $transaction->shipping_address_id = $shippingAddressId;
            $transaction->billing_address_id = $billingAddressId;
            $transaction->card_last_digits = $cardLastDigits;
            $transaction->paid_value = str_replace(',', '.', $book->buy_price);
            $transaction->payment_method = $payMethod;
            $transaction->rent_duration = $rentDur;
            $transaction->status = $TRANSACTION_STATUS_BUYED;
            $transaction->book_id = $booksIds[$i];
            $transaction->seller_id = $sellerIds[$i];

            $messages[$i] = 'error bookId='.$transaction->book_id;

            $book = DB::table('books')->where('id', $transaction->book_id)->first();
            if ($book->isActive && ($book->buy_quantity > 0))
            {
              if($transaction->save()){
                  $messages[$i] = 'sucess bookId='.$transaction->book_id;
                  $transactions[$i] = $transaction;
                  $this->takeOutOneBookUnity($transaction->book_id);
                }
                else {
                  $transactions[$i] = $transaction;
                }
            } else {
              $messages[$i] = 'inactive bookId='.$transaction->book_id;
			  return response()->json(array('message' => $messages));
            }
        }

      return response()->json(array('message' => $messages, 'transactions' => $transactions));
    }

    public function takeOutOneBookUnity($id)
    {
      $book = Book::where('id', $id)->first();
      DB::table('books')->where('id', $id)->update(['buy_quantity' => ($book->buy_quantity - 1)]);
      if (($book->buy_quantity - 1) == 0) {
        DB::table('books')->where('id', $id)->update(['isActive' =>  0]);
      }
    }


    public function makePayments($request)
    {
      $message = '';
      $token = '8OXBCUPZ1O7A291VJIXNJXH1MWUNMMIO';
      $key = 'R4XOOOFZ71S7OFIDUZHS9G33HFAAZG8RSXFZJYOY';
      $moip = new Moip(new BasicAuth($token, $key), Moip::ENDPOINT_SANDBOX);

      $transactionsIds = $request->get('transactionsIds');

      $contador = 0;
      foreach ($transactionsIds as $id)
      {
        $transaction[$contador] = DB::table('transactions')
                ->where('id', $id)
                ->first();

       $customer = $this->getOrCreateMoipCustomer($transaction[0], $request->nameOnCard, $request->get('cpf'), $moip);

       $orders[$contador] = $this->createMoipOrder($transaction[$contador], $customer, $moip);
       if($orders[$contador] == NULL)
         return 'fail pegando ou criando order';
       $contador++;
      }


      if($customer == NULL)
        return 'fail pegando ou criando costumer';


      // $multiorder = $this->createMoipMutiorder($transaction, $orders, $moip);
      // if($multiorder == NULL)
      //   return 'fail criando multiorder';

      $hash = NULL;
      if ($request->has('cardHash')) {
                $hash = $request->cardHash;
      }

      $payment = $this->MakeMoipPayment($orders, $customer, $hash);
      // $payment = $multiorder->multipayments()
      //   ->setCreditCardHash($hash, $customer)
      //   ->setInstallmentCount(3)
      //   ->setStatementDescriptor('teste de pag')
      //   ->execute();

        return 'success';
    }

    public function getOrCreateMoipCustomer($transaction, $name, $cpf, $moip)
    {

      $moipOwnIdBase = 'bookTable:';
      $moipOwnIdIdentifier = 'user_idenn:';

      $user = DB::table('users')
              ->where('id', $transaction->buyer_id)
              ->first();

      if ($user->moip_user_id) {
        return $moip->customers()->get($user->moip_user_id);
      }

      $shippingAddress = DB::table('addresses')
              ->where('id', $transaction->shipping_address_id)
              ->first();

      $billingAddress = DB::table('addresses')
              ->where('id', $transaction->billing_address_id)
              ->first();

	  printf("email: ".$user->email);
      try 
	  {
		if ($billingAddress) 
		{
          $customer = $moip->customers()->setOwnId($moipOwnIdBase.$moipOwnIdIdentifier.$user->id)
            ->setFullname($name)
            ->setEmail(strtolower ($user->email))
            // ->setBirthDate('')
            ->setTaxDocument($cpf)
            // ->setPhone(11, 66778899)
            ->addAddress('SHIPPING',
                $shippingAddress->street, $shippingAddress->number,
                'Bairro', $shippingAddress->city, $shippingAddress->uf,
                $shippingAddress->cep, $shippingAddress->complement)
			->addAddress('BILLING',
                $billingAddress->street, $billingAddress->number,
                'Bairro', $billingAddress->city, $billingAddress->uf,
                $billingAddress->cep, $billingAddress->complement);
		} else 
		{
			$customer = $moip->customers()->setOwnId($moipOwnIdBase.$moipOwnIdIdentifier.$user->id)
            ->setFullname($name)
            ->setEmail($user->email)
            // ->setBirthDate('')
            ->setTaxDocument($cpf)
            // ->setPhone(11, 66778899)
            ->addAddress('SHIPPING',
                $shippingAddress->street, $shippingAddress->number,
                'Bairro', $shippingAddress->city, $shippingAddress->uf,
                $shippingAddress->cep, $shippingAddress->complement)
			->addAddress('BILLING',
                $shippingAddress->street, $shippingAddress->number,
                'Bairro', $shippingAddress->city, $shippingAddress->uf,
                $shippingAddress->cep, $shippingAddress->complement);
		}

            $customer = $customer->create();
            // $user->moip_user_id = $customer->getId();
            // $user->update(['moip_user_id' => $customer->getId()]);
            DB::table('users')->where('id', $transaction->buyer_id)->update(['moip_user_id' => $customer->getId()]);
            return $customer;
      } 
	  catch (\Moip\Exceptions\UnautorizedException $e) {
            //StatusCode 401
            echo $e->getMessage();
      }
	  catch (\Moip\Exceptions\ValidationException $e) {
            //StatusCode entre 400 e 499 (exceto 401)
            printf($e->__toString());
      } 
	  catch (\Moip\Exceptions\UnexpectedException $e) {
            //StatusCode >= 500
            echo $e->getMessage();
      }
      return NULL;
    }

    public function createMoipOrder($transaction, $customer, $moip)
    {

      if ($transaction->moip_order_id) {
        return $moip->orders()->get($transaction->moip_order_id);
      }
      $moipOwnIdBase = 'bookTable:';
      $moipOwnIdIdentifier = 'transaction_id:';

      $receiver = DB::table('users')
              ->where('id', $transaction->seller_id)
              ->first();

      $book = DB::table('books')
            ->where('id', $transaction->book_id)
            ->first();

      // TODO: CHECK IF BOOK IS AVAILABLE

      $formattedPrice = str_replace(',', '', $book->buy_price);
      $formattedPrice = str_replace('.', '', $book->buy_price);
      printf($formattedPrice);
      $order = $moip->orders()->setOwnId($moipOwnIdBase.$moipOwnIdIdentifier.$transaction->id)
      ->addItem($book->title,1, $book->author, (int)$formattedPrice)
      ->setShippingAmount(300)
      // ->setAddition(1000)
      // ->setDiscount(5000)
      ->setCustomer($customer);
      // ->addReceiver($receiver->moip_user_id, 'PRIMARY', NULL, 90, false);
      // ->addReceiver('MPA-IFYRB1HBL73Z', 'SECONDARY', NULL, 10, true);
      try {
        $order = $order->create();

        // $transaction->moip_transcation_id = $order->getId();
        DB::table('transactions')->where('id', $transaction->id)->update(['moip_order_id' => $order->getId()]);
        // $transaction.save();
      } catch (Exception $e) {
        printf($e->__toString());
      }
      return $order;
    }

    public function createMoipMutiorder($transaction, $orders, $moip)
    {

      if ($transaction[0]->moip_multiorder_id) {
        return $moip->multiorders()->get($transaction[0]->moip_multiorder_id);
      }

      $moipOwnIdBase = 'sss:';
      $moipOwnIdIdentifier = 'multitransaction_id:';

      $multiorder = $moip->multiorders()
        ->setOwnId($moipOwnIdBase.$moipOwnIdIdentifier.$transaction[0]->id);
      foreach ($orders as $order)
      {
        $multiorder->addOrder($order);
      }

      try {
            $multiorder = $multiorder->create();
            $contador = 0;
            foreach ($multiorder->orders as $order)
            {
              $transaction[$contador]->moip_transcation_id = $order->getId();
              $transaction[$contador]->moip_multiorder_id = $multiorder->getId();
              $transaction[$contador]->save();
              $contador++;
            }
            return $multiorder;
          } catch (\Moip\Exceptions\UnautorizedException $e) {
            //StatusCode 401
            printf($e->getMessage());
          } catch (\Moip\Exceptions\ValidationException $e) {
            //StatusCode entre 400 e 499 (exceto 401)
            printf('veio 4');
            printf($e->__toString());
          } catch (\Moip\Exceptions\UnexpectedException $e) {
            //StatusCode >= 500
            printf($e->getMessage());
          }
      return NULL;
    }

    public function MakeMoipPayment($orders, $customer, $hash){
        // $hash = 'i1naupwpTLrCSXDnigLLTlOgtm+xBWo6iX54V/hSyfBeFv3rvqa1VyQ8/pqWB2JRQX2GhzfGppXFPCmd/zcmMyDSpdnf1GxHQHmVemxu4AZeNxs+TUAbFWsqEWBa6s95N+O4CsErzemYZHDhsjEgJDe17EX9MqgbN3RFzRmZpJqRvqKXw9abze8hZfEuUJjC6ysnKOYkzDBEyQibvGJjCv3T/0Lz9zFruSrWBw+NxWXNZjXSY0KF8MKmW2Gx1XX1znt7K9bYNfhA/QO+oD+v42hxIeyzneeRcOJ/EXLEmWUsHDokevOkBeyeN4nfnET/BatcDmv8dpGXrTPEoxmmGQ==';

        if ($hash) {
          try {

            foreach ($orders as $order){

              $payment = $order->payments()
              ->setCreditCardHash($hash, $customer)
	             ->setInstallmentCount(0)
	              ->setStatementDescriptor('teste de pag')
	               ->setDelayCapture(false)
	                ->execute();
                  DB::table('transactions')->where('moip_order_id', $order->getId())->update(['moip_payment_id' => $payment->getId()]);
              }
            } catch (Exception $e) {
              printf($e->__toString());
            }
        } else {
            // Multipagamento com boleto
            $logo_uri = 'https://cdn.moip.com.br/wp-content/uploads/2016/05/02163352/logo-moip.png';
            $expiration_date = new DateTime();
            $instruction_lines = ['INSTRUÇÃO 1', 'INSTRUÇÃO 2', 'INSTRUÇÃO 3'];
            try {
              foreach ($orders as $order){
                $payment = $order->payments()
                  ->setBoleto($expiration_date, $logo_uri, $instruction_lines)
                  ->execute();
                  DB::table('transactions')->where('moip_order_id', $order->getId())->update(['moip_payment_id' => $payment->getId()]);
                }
            } catch (Exception $e) {
              printf($e->__toString());
            }
        }
        return $payment;
    }
}
