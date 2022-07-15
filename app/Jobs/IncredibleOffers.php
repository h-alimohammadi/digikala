<?php

namespace App\Jobs;

use App\Offer;
use App\ProductWarranty;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class IncredibleOffers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $row_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->row_id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $productWarranty = ProductWarranty::findOrFail($this->row_id);

        if ($productWarranty && $productWarranty->offers==1) {
            $time = time();
            if ($productWarranty->offers_first_time <= $time){
                $offers = new Offer();
                $offers->remove($productWarranty);
            }

        }
    }
}
