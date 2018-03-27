<?php

namespace App\Managers;

use App\Model\Book;
use GuzzleHttp\Client;

class OpenLibrary
{
    protected $client;

    public function __construct()
    {
        $this->client =  new Client();
    }

    protected function request(string $uri,
                            array $parameters = [])
    {
      $uri = "$uri?" . http_build_query($parameters);
      $response = $this->client->request('GET', $uri);

      if ($response->getStatusCode() !== 200) {
        throw new OpenLibraryException($response);
      }
      
      return json_decode($response->getBody()->getContents(), true);
    }

    public function search($query, $offset=0)
    {
      $uri = "http://openlibrary.org/search.json";
      $parameters = [
                'q' => $query,
                'offset' => $offset
                ];
      $response = $this->request($uri, $parameters);
      $books = $this->nest_array($response, "docs");
      $isbn_array = [];
      foreach ($books as $book) {
        $isbns = $this->nest_array($book, "isbn");
        if (!empty($isbns)) {
            foreach ($isbns as $isbn) {
                array_push($isbn_array, $isbn);
            }
        }
      }
      return [
        'total' => $response['num_found'],
        'start' => $response['start'],
        'subtotal' => count($response['docs']),
        'isbns' => $isbn_array,
      ];
    }

    public function book($isbn)
    {
        $uri = "https://openlibrary.org/api/books";
        $parameters = [
                    'bibkeys' => "ISBN:$isbn",
                    'jscmd' => 'data',
                    'format' => 'json',
                ];
        $json = $this->request($uri, $parameters);
        $book = new Book();
        $book->isbn         = $isbn;
        $book->title        = $this->nest_array($json, "ISBN:$isbn", "title");
        $book->description  = $this->nest_array($json,
                                                "ISBN:$isbn",
                                                "subtitle");
        $book->author_name  = $this->nest_array($json,
                                                "ISBN:$isbn",
                                                "author_name",
                                                0);
        return $book;
    }

    private function nest_array(array $source, ...$keys)
    {
        $aux = $source;
        foreach ($keys as $key) {
            if (array_key_exists($key, $aux)) {
                $aux = $aux[$key];
            } else {
                return null;
            }
        }
        return $aux;
    }

}
