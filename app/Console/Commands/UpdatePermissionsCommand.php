<?php

namespace App\Console\Commands;

use App\Jobs\AuctionWinnerJob;
use App\Models\Auction;
use App\Models\Permission;
use App\Models\SafeBox;
use App\Models\SmsBox;
use App\Models\Wallet;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class UpdatePermissionsCommand extends Command
{
    // The name and signature of the console command.
    protected $signature = 'update:permissions';
    protected $description = 'Force truncate selected tables';

    public function handle()
    {
        foreach ($this->getPermissions() as $permission) {
            //dump($permission->name);
            Gate::define($permission->name, function ($user) use ($permission) {
                return $user->level == 'creator' or $user->hasRole($permission->roles);
            });
        }
    }

    protected function getPermissions()
    {
        return Permission::where('active', true)->with('roles')->get();
    }
}
