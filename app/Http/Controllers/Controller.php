<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $createdMessage;
    protected $updatedMessage;
    protected $deletedMessage;
    protected $noPermission;

    public function __construct()
    {
        $this->createdMessage = __('Data successfully created.');
        $this->updatedMessage = __('Data successfully updated.');
        $this->deletedMessage = __('Data successfully deleted.');
        $this->noPermission = __('You have no related permission.');
    }
}
