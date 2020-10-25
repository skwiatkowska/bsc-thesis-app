<?php

namespace App\Console\Commands;

use App\Entities\BookItem;
use App\Entities\Reservation;

use Illuminate\Console\Command;

class DeleteExpiredReservations extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete-reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired book reservations and make books available again';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {
        \Log::info("Deleting expired reservations");
        $now = new \DateTime();
        $expired = Reservation::with('bookItem')->where('due_date', '<', $now)->get();
        \Log::info($expired->count());

        foreach($expired as $exp){
            $item = $exp->bookItem;
            $item->update(['status' => BookItem::AVAILABLE]);
            $exp->delete();
            \Log::info("Deleting ".$exp->id);
        }

        \Log::info('Deleted '.$expired->count(). ' reservation(s)');
    }
}