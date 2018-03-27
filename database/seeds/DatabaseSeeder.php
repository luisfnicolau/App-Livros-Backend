<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminTableSeeder::class);
        $this->call(PricingTableSeeder::class);
        if(env('APP_ENV')=='local')
        {
            $this->call(FacebookUsersSeeder::class);
            $this->call(BookTableSeeder::class);
            $this->call(BookCopyTableSeeder::class);
            $this->call(OrderTableSeeder::class);
        }
    }
}

class AdminTableSeeder extends Seeder
{
    public function run(){
        if(\App\Model\Admin::
            where('email', 'admin@app-livros.com')->count() ==0){
            \App\Model\Admin::create([
                    'name' => 'Admin Zero',
                    'email' => 'admin@app-livros.com',
                    'password' => bcrypt('temp-password'),
                ]);
        }
    }
}

class PricingTableSeeder extends Seeder
{
    public function run(){
        if(\App\Model\Pricing::count() == 0){
            \App\Model\Pricing::create([
                           'id' => \App\Model\Pricing::ID_FREE,
                           'name' => 'Grátis até 50 livros',
                           'description' => 'até 50 livros',
                           'price' => 0,
                           'max_books' => 50,
                                   ]);
            \App\Model\Pricing::create([
                           'name' => 'De 51 a 1000 livros',
                           'price' => 15,
                           'max_books' => 1000,
                                   ]);
            \App\Model\Pricing::create([
                           'name' => 'De 1001 a 2000 livros',
                           'price' => 30,
                           'max_books' => 2000,
                                   ]);
        }
    }
}

class FacebookUsersSeeder extends Seeder
{
    public function run(){
        $this->command->comment('olar');
        $this->command->comment(env('FACEBOOK_APP_ID'));
        $this->command->comment('olar');
        if(\App\User::count() > 10){
            $this->command
                    ->comment("Already has more than 10 users.\n"
                            ."Skiping user creation");
            return;
        }
        $faker = \Faker\Factory::create();
        foreach(range(0,10) as $i) {
            $user = new \App\User([
                'name' => "$faker->firstName $faker->lastName",
                'created_at' => $faker->dateTimeBetween('-6 years', 'now')
            ]);
            $facebook = new \Facebook\Facebook([
                'app_id' => env('FACEBOOK_APP_ID'),
                'app_secret' => env('FACEBOOK_APP_SECRET'),
            ]);

            $access_token = env('FACEBOOK_APP_ID')
                            ."|".env('FACEBOOK_APP_SECRET');
            $response = $facebook
                ->post('/'.env('FACEBOOK_APP_ID').'/accounts/test-users',
                [
                    'name' => $user->name,
                    'password' => '123456',
                    'installed' => 'true',
                    'access_token' => 
                        env('FACEBOOK_ACCESS_TOKEN', $access_token),
                ]
            );
            if($response->getHttpStatusCode()===200){
                $fbUser = $response->getGraphUser();
                $user->facebook_id = $fbUser->getId();
                $user->email = $fbUser->getEmail();
                $user->facebook_token = $fbUser->getField('access_token');
                $user->save();
                $this->command
                    ->comment("User $i -> $user->name created succesfully");
            }
            else{
                $this->command->error("Error in request");
                break;
            }
        }
    }
}

class BookTableSeeder extends Seeder
{
    public function run(){
        if(\App\Model\Book::count()==0){
            $faker = \Faker\Factory::create();
            // $faker->addProvider(new \Faker\Provider\Book($faker));
            foreach(range(0,20) as $i) {
                \App\Model\Book::create([
                        'title' => $faker->sentence($faker->numberBetween(1, 5)),
                        'description' => $faker->text,
                        'isbn' => $faker->isbn13,
                        'author' => $faker->name,
                        'cover' => $faker->imageURL(),
                        'seller_id' => 1,
                    ]);
            }
        } else {
            $this->command->info('Already has books in db. Skiping seeder');
        }
    }
}


class BookCopyTableSeeder extends Seeder
{
    public function run(){
        if(\App\Model\BookCopy::count()==0){
            $faker = \Faker\Factory::create();
            foreach(range(0,20) as $i) {
                $copy = new \App\Model\BookCopy([
                        'message' => $faker->text,
                        'photo' => $faker->imageURL(),
                        'price' => $faker->randomFloat(2, 1, 50),
                    ]);
                $user = \App\User::inRandomOrder()->first();
                $copy->user()->associate($user);
                $book = \App\Model\Book::inRandomOrder()->first();
                $copy->book()->associate($book);
                $copy->save();
            }

        } else {
            $this->command->info('Already has bookcopies. Skiping seeder');
        }
    }
}

class OrderTableSeeder extends Seeder
{
    public function run(){
        \DB::table('orders')->delete();
        if(\App\Model\Order::count()<10){
            $faker = \Faker\Factory::create();
            foreach(range(0,100) as $i) {
                \DB::transaction(function () use($faker) {
                    $order = new \App\Model\Order([
                                      'rating' => $faker->numberBetween(0, 10)
                                  ]);
                    $order->created_at = \Carbon\Carbon::now()->addDays($faker->numberBetween(0,10))->addHours($faker->numberBetween(0,23));
                    $owner = \App\User::inRandomOrder()->first();
                    $renter = \App\User::inRandomOrder()->first();
                    $order->owner()->associate($owner);
                    $order->renter()->associate($renter);

                    $order->latitude = -22.9116 + $faker->numberBetween(-10, 10)/100;
                    $order->longitude = -43.1883 + $faker->numberBetween(-10, 10)/100;

                    $order->total = 0;
                    $order->delivery_date = $order->created_at->addDay($faker->numberBetween(0,10));
                    $order->canceled = $faker->boolean();
                    $order->canceled_date = $order->created_at->addDay($faker->numberBetween(0,5));
                    $order->save();

                    if($faker->boolean(30)){
                      $reversal = new \App\Model\PaymentReversalRequest(['fulfilled' => $faker->boolean(50)]);
                      $reversal->save();

                      $order->paymentReversalRequest()->save($reversal);
                    }

                    foreach($owner->copies as $copy){
                        if($faker->boolean(80) && $copy->available()){
                            $order->total += $copy->price;
                            $order->copies()->attach($copy);
                            $copy->renter()->associate($renter);
                            $copy->save();
                        }
                    }
                    $order->save();
                });
            }

        } else {
            $this->command->info('Already has orders. Skiping seeder');
        }
    }
}
