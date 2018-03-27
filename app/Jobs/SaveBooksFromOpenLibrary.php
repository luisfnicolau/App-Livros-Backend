<?php

namespace App\Jobs;

use App\Managers\OpenLibrary;
use App\Model\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Log;

class SaveBooksFromOpenLibrary implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $searchQuery;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $searchQuery)
    {
        $this->searchQuery = $searchQuery;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $openLibrary = new OpenLibrary();
        $isbn_array = [];
        Log::info("Starting job\n");
        do {
            $offset = 0;
            $response_json = $openLibrary->search($this->searchQuery, $offset);
            $isbn_array = array_merge($isbn_array, $response_json['isbns']);
            $offset += $response_json['subtotal'];
            $total = $response_json['total'];
        } while ($offset < $total);
        Log::info("Collected all isbn numbers\n");
        Log::info("Total was: " . count($isbn_array) . ".\n");

        foreach ($isbn_array as $isbn) {
            $book = $openLibrary->book($isbn);
            if (Book::where('isbn', $isbn)->count() == 0) {
                $book->save();
            }
            Log::info("Book $book->title saved\n");
        }
        Log::info("Job done\n");
    }
}
