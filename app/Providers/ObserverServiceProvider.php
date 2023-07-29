<?php

namespace App\Providers;

use MaxDev\Models\Call;
use MaxDev\Models\Invoice;
use MaxDev\Models\PharmacyPrescription;
use MaxDev\Models\Subscription;
use MaxDev\Observers\CallObserver;
use MaxDev\Observers\CallRecommendationObserver;
use MaxDev\Observers\InvoiceObserver;
use MaxDev\Observers\NotificationObserver;
use MaxDev\Observers\AppointmentObserver;
use MaxDev\Observers\PharmacyPrescriptionObserver;
use MaxDev\Observers\SubscriptionObserver;
use MaxDev\Observers\TransactionObserver;
use MaxDev\Observers\CallReportObserver;
use Illuminate\Support\ServiceProvider;
use MaxDev\Models\CallRecommendation;
use MaxDev\Observers\AdminObserver;
use MaxDev\Models\Notification;
use MaxDev\Models\Appointment;
use MaxDev\Models\Transaction;
use MaxDev\Models\CallReport;
use MaxDev\Models\Admin;

class ObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     * @return void
     */
    public function boot()
    {
        
    }
}
