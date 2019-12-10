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
    protected $unauthorizedMessage;
    protected $restoredMessage;
    protected $savedSettingMessage;

    public function __construct()
    {
        $this->createdMessage = __('Data successfully created.');
        $this->updatedMessage = __('Data successfully updated.');
        $this->deletedMessage = __('Data successfully deleted.');
        $this->noPermission = __('You have no related permission.');
        $this->unauthorizedMessage = __('This action is unauthorized.');
        $this->restoredMessage = __('Data successfully restored.');
        $this->savedSettingMessage = __('Settings saved successfully.');
    }
}
