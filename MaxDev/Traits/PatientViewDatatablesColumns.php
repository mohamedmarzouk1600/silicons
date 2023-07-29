<?php
/**
 * @author Mohamed Marzouk <mohamedmarzouk1600@gmail.com>
 * @FileCreated 12/5/20 5:15 AM
 */

namespace MaxDev\Traits;

use MaxDev\Services\PlanService;

trait PatientViewDatatablesColumns
{
    /**
     * Notice :- this name of tab that you want to be active when open show blade of patient.
     * For get the name you want to put here :-
     * open this directory ( resources/views/admin/patient/tabs )
     * and get the name of file without ( .blade.php )
     * Ex :- ( appointments.blade.php ) so write here ( appointments ).
     *
     * @var string
     */
    public string $activeTab = 'calls';

    public function columns()
    {
        /**
         * Here write cols for datatable in show blade.
         */
        return [
            [
                'appointments_cols_controller' => [
                    'appointment_type', 'appointment_name', 'appointment_address', 'booking', 'status', 'created_at', 'action'
                ],
                'appointments_cols_html' => [
                    __('Appointment Type'), __('Appointment Name'), __('Appointment Address'), __('Booking'),
                    __('Status'), __('Created at'), __('Action')
                ]
            ],
            [
                'calls_cols_controller' => [
                    'id', 'call_from', 'nurse', 'date', 'duration', 'doctor',
                    'recommendations', 'action'
                ],
                'calls_cols_html' => [
                    __('Call'), __('Call from'), __('Nurse'), __('Date'), __('Duration'),
                    __('Doctor'), __('Recommendations'), __('Action')
                ]
            ],
            [
                'customer_support_case_cols_controller' => [
                    'title', 'description', 'status', 'type', 'action'
                ],
                'customer_support_case_cols_html' => [
                    __('Title'), __('Description'), __('Status'), __('Type'), __('View Details')
                ]
            ],
            [
                'DependanceAccount_cols_controller' => [
                    'id',
                    'name',
                    'email',
                    'mobile',
                    'status',
                    'joined',
                    'lang',
                    'social',
                    'platform',
                    'action'
                ],
                'DependanceAccount_cols_html' => [
                    __('ID'),
                    __('Name'),
                    __('Email'),
                    __('Mobile'),
                    __('Status'),
                    __('Joined'),
                    __('Lang'),
                    __('Social'),
                    __('Platform'),
                    __('Action')
                ]
            ],
            [
                'activity_log_cols_controller' => [
                    'id',
                    'log_name',
                    'description',
                    'subject_id',
                    'subject_type',
                    'event',
                    'ip',
                    'user_agent',
                    'url',
                    'method',
                    'created_at',
                ],
                'activity_log_cols_html' => [
                    __('ID'),
                    __('Log Name'),
                    __('Description'),
                    __('Subject Id'),
                    __('Subject Type'),
                    __('Event'),
                    __('IP'),
                    __('User Agent'),
                    __('URL'),
                    __('Method'),
                    __('Created At')
                ]
            ],
            [
                'files_cols_controller' => [
                    'id', 'name', 'file_type', 'action'
                ],
                'files_cols_html' => [
                    __('ID'), __('Name'), __('File Type'), __('Action')
                ]
            ],
            [
                'notifications_cols_controller' => [
                    'title', 'body', 'read', 'sent_at', 'type'
                ],
                'notifications_cols_html' => [
                    __('Title'), __('Body'), __('Read'), __('Sent at'), __('Type')
                ]
            ],
            [
                'prescriptions_cols_controller' => [
                    'id', 'name', 'file_type', 'digital_prescriptions', 'action'
                ],
                'prescriptions_cols_html' => [
                    __('ID'), __('Name'), __('File Type'), __('Count Digital'), __('Action')
                ]
            ],
            [
                'readings_cols_controller' => [
                    'call_id', 'reading_type', 'reading_at', 'reading_condition', 'value', 'unit', 'created_by'
                ],
                'readings_cols_html' => [
                    __('Call'), __('Reading Type'), __('Reading At'), __('Reading Condition'),
                    __('Value'), __('Unit'), __('Created By')
                ]
            ],
            [
                'transactions_cols_controller' => [
                    'created_by', 'amount', 'payment_method', 'payment_status', 'type', 'created_at'
                ],
                'transactions_cols_html' => [
                    __('Created By'), __('Amount'), __('Payment Method'), __('Payment Status'),
                    __('Type'), __('Create At')
                ]
            ],
            [
                'subscriptions_cols_controller' => [
                    'plan', 'start_at', 'end_at', 'renews_at', 'created_by', 'status', 'action'
                ],
                'subscriptions_cols_html' => [
                    __('Plan'), __('Start at'), __('End at'), __('Renews at'), __('Created by'), __('Status'), __('Action')
                ],
                'additional_params' => [
                    'plans' => (new PlanService())->adminIndex()->active()->get()->pluck('name', 'id')
                ]
            ],
            [
                'menstrual_cols_controller' => [
                    'start_at', 'ovulation', 'end_at', 'action'
                ],
                'menstrual_cols_html' => [
                    __('Start at'), __('Ovulation'), __('End at'), __('Action'),
                ]
            ],
            [
                'diabetes_program_actions_cols_controller' => [
                    'name', 'interval', 'interval_count', 'recurring', 'action'
                ],
                'diabetes_program_actions_cols_html' => [
                    __('name'), __('Interval'), __('Interval Count'), __('Recurring'), __('Action'),
                ]
            ],

        ];
    }
}
