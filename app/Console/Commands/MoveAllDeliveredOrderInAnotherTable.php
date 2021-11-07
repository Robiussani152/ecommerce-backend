<?php

namespace App\Console\Commands;

use App\Services\OrderService;
use Illuminate\Console\Command;

class MoveAllDeliveredOrderInAnotherTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:delivered';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will help you to move all delivered order in amother table';
    public $orderService;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->orderService->moveAllDeliveredOrder();
        return Command::SUCCESS;
    }
}
