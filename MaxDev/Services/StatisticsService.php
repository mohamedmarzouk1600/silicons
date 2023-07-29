<?php

namespace MaxDev\Services;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use MaxDev\Enums\ActivitylogPatient;
use MaxDev\Enums\CallStatus;
use MaxDev\Enums\PaymentMethod;
use MaxDev\Enums\PaymentStatus;
use MaxDev\Enums\RecommendationTypes;
use MaxDev\Enums\Type;
use MaxDev\Enums\UserGroupType;
use MaxDev\Models\Admin;
use MaxDev\Models\Call;
use MaxDev\Models\Category;
use MaxDev\Models\Coupon;
use MaxDev\Models\Laboratory;
use MaxDev\Models\NanoClinic;
use MaxDev\Models\Patient;
use MaxDev\Models\Radiology;
use MaxDev\Models\RadiologyBranch;
use MaxDev\Models\Specialty;
use MaxDev\Models\Transaction;
use ReflectionException;

class StatisticsService
{
    public function calls_query(array $filters, $limit = null): Builder
    {
        return Call::when(
            ($statuses = Arr::get($filters, 'statuses')) && is_array($statuses) && count($statuses),
            function ($query) use ($statuses) {
                $query->whereIn('calls.status', $statuses);
            }
        )
            ->whereNotIn('calls.patient_id', [
                '975c5a6c-7b3a-452f-8ec1-bb64caad00fe', // Mostafa
                '97596880-02aa-4941-8ffe-d60bd5dbe439', // Seif
                '977555d4-32ef-47b6-8498-accb3e7ccbb9', // Ahmed Khames
                '9759793d-fac4-41e0-bfa7-85e9c855e29d', // yousef reda
                '975f978b-d507-474f-aaa7-9c00aa727fd7', // Mostafa AbdElmmohsen
                '97ec3936-ac57-4453-8102-d6d37258f9cd', // Laila
                '9761596c-6002-485b-adba-3fd4908c953d', // Reda
                '9759871c-7178-4fe2-8f43-77c3b10e1609', //Tarek
                '9783e94e-bfd8-4d1d-bb17-14ec06a4e2b2', // Ahmed Eid
                '97597235-7c8e-4244-a788-1698d605701e', //
                '975f9fc6-9218-464d-a995-1721fef864c4',
                '9785b6bd-dc19-42fe-858a-4505206b3ea7',
                '97afe0f2-6508-4c65-b3e7-44b49a13b253',
                '9787ac58-8bea-458b-a350-80ce824ae26c',
                '975fa04d-e885-4c49-a4c5-25acdb10fa4a',
                '97615bcb-9ee1-4493-88a1-cc506e16b154',
                '9785b6bd-dc19-42fe-858a-4505206b3ea7',
                '97f69b86-4535-49bf-90e2-3df2a96a87ac',
            ])
            ->whereNotIn('calls.doctor_id', [
                '976db05a-119a-4782-b882-f897ff66bda0',
                '9777874d-f379-451a-be7f-827cc327c0cd',
                '9785b7fa-ebb1-4443-ab85-39760da19158',
            ])
            ->when($date_from = Arr::get($filters, 'date_from'), static function ($query) use ($date_from) {
                $query->where('calls.created_at', '>=', Carbon::parse($date_from));
            })
            ->when($date_to = Arr::get($filters, 'date_to'), static function ($query) use ($date_to) {
                $query->where('calls.created_at', '<=', Carbon::parse($date_to));
            })
            ->when($doctor_id = Arr::get($filters, 'doctor_id'), static function ($query) use ($doctor_id) {
                $query->where('calls.doctor_id', $doctor_id);
            })
            ->when($limit, static function ($query) use ($limit) {
                $query->limit($limit);
            });
    }


    public function calls_count_by_caller_type(array $filters, $limit = null): array
    {
        $query = $this->calls_query($filters, $limit)->select([
            'caller_type',
            'coupon_id',
            DB::raw("IFNULL(COUNT(calls.id), 0) calls_count"),
            DB::raw("date_format(calls.created_at, '%Y-%m-%d') calls_day"),
        ])
            ->groupBy(DB::raw("calls.caller_type,calls_day,coupon_id"))
            ->get();
        $data = [];
        $data['labels'] = $query->unique('calls_day')->pluck('calls_day')->toArray();
        foreach ($data['labels'] as $day) {
            $data['lines']['nanoclinic'][] = $query->where('calls_day', $day)->where(
                'caller_type',
                NanoClinic::class
            )->sum('calls_count');

            $data['lines']['patient'][] = $query->where('calls_day', $day)->where(
                'caller_type',
                Patient::class
            )->whereNotIn('coupon_id', Coupon::$pharmacy)->sum('calls_count');

            $data['lines']['pharmacy'][] = $query->where('calls_day', $day)->where(
                'caller_type',
                Patient::class
            )->whereIn('coupon_id', Coupon::$pharmacy)->sum('calls_count');
        }
        return $data;
    }


    public function calls_count_by_doctor(array $filters, $limit = null): array
    {
        $query = $this->calls_query($filters, $limit)->select([
            'doctor_id',
            'users.fullname AS doctor_name',
            DB::raw("IFNULL(COUNT(calls.id), 0) calls_count"),
            DB::raw("date_format(calls.created_at, '%Y-%m-%d') calls_day"),
        ])
            ->join('users', 'users.id', 'doctor_id')
            ->groupBy(DB::raw("calls.doctor_id,calls_day"))
            ->get();

        $data = [];
        $data['labels'] = $query->unique('calls_day')->pluck('calls_day')->toArray();
        $doctors = $query->unique('doctor_name')->pluck('doctor_name')->toArray();
        foreach ($query->groupBy('calls_day') as $day => $rows) {
            foreach ($doctors as $doctor) {
                $data['lines'][$doctor][] = $rows->where(
                    'doctor_name',
                    $doctor
                )->first()?->calls_count ?? 0;
            }
        }
        return $data;
    }


    public function all_calls_count_by_doctors(array $filters, $limit = null): array
    {
        $query = $this->calls_query($filters, $limit)->select([
            'doctor_id',
            'users.fullname AS doctor_name',
            DB::raw("IFNULL(COUNT(calls.id), 0) calls_count"),
        ])
            ->join('users', 'users.id', 'doctor_id')
            ->whereNotIn('doctor_id', [
                '976db05a-119a-4782-b882-f897ff66bda0',
                '9785b7fa-ebb1-4443-ab85-39760da19158',
                '9777874d-f379-451a-be7f-827cc327c0cd',
            ])
            ->groupBy(DB::raw("calls.doctor_id"));

        return $query->pluck('calls_count', 'doctor_name')->toArray();
    }

    public function registered_patients_per_day(array $filters, $limit = null): array
    {
        $query = Patient::select([
            DB::raw("IFNULL(COUNT(users.id), 0) patients_count"),
            DB::raw("date_format(users.created_at, '%Y-%m-%d') register_day"),
        ])
            ->when($date_from = Arr::get($filters, 'date_from'), static function ($query) use ($date_from) {
                $query->where('users.created_at', '>=', Carbon::parse($date_from)->startOfDay());
            })
            ->groupBy(DB::raw("register_day"))
            ->get();
        $data = [];
        $data['labels'] = $query->unique('register_day')->pluck('register_day')->toArray();
        $data['lines'][] = $query->unique('register_day')->pluck('patients_count')->toArray();
        return $data;
    }

    public function registered_patients_accumulative(array $filters, $limit = null): array
    {
        $query = Patient::select([
            DB::raw("IFNULL(COUNT(users.id), 0) patients_count"),
            DB::raw("date_format(users.created_at, '%Y-%m-%d') register_day"),
        ])
            ->when($date_from = Arr::get($filters, 'date_from'), static function ($query) use ($date_from) {
                $query->where('users.created_at', '>=', Carbon::parse($date_from));
            })
            ->groupBy(DB::raw("register_day"))
            ->orderBy(DB::raw("register_day"))
            ->get();
        if ($date_from = Arr::get($filters, 'date_from')) {
            $patientsCount = Patient::where('created_at', '<', $date_from)->count() ?? 0;
        } else {
            $patientsCount = 0;
        }
        $labels = $query->unique('register_day')->pluck('register_day')->toArray();
        $lines = $query->pluck('patients_count')->toArray();
        $newLines = [];
        foreach ($lines as $line) {
            $patientsCount = $patientsCount + $line;
            $newLines[] = $patientsCount;
        }
        $data = [];
        $data['labels'] = $labels;
        $data['lines'][] = $newLines;
        return $data;
    }


    public function patients_calls(array $filters, $limit = null): array
    {
        $query = $this->calls_query($filters, $limit)->select([
            'coupon_id',
            DB::raw("IFNULL(COUNT(calls.id), 0) calls_count"),
            DB::raw("date_format(calls.created_at, '%Y-%m-%d') calls_day"),
        ])
            ->groupBy(DB::raw("calls_day"))
            ->get();
        $data = [];
        $data['labels'] = $query->unique('calls_day')->pluck('calls_day')->toArray();
        if ($date_from = Arr::get($filters, 'date_from')) {
            $patientsCalls = $this->calls_query([
                'statuses' => [CallStatus::Finished],
                'date_to' => $date_from,
            ])->count() ?? 0;
        } else {
            $patientsCalls = 0;
        }
        foreach ($data['labels'] as $day) {
            $patientsCalls += $query->where('calls_day', $day)->sum('calls_count');
            $data['lines']['calls'][] = $patientsCalls;
        }
        return $data;
    }

    public function providerOnboarded(): int
    {
        return DB::select("SELECT count(DISTINCT users.id) as provider_count FROM `personal_access_tokens` INNER JOIN users ON users.id = personal_access_tokens.tokenable_id AND users.user_group = 5; ")[0]->provider_count;
    }


    public function patient_calls_per_day(array $filters, $limit = null): array
    {
        $query = $this->calls_query($filters, $limit)->select([
            'caller_type',
            'coupon_id',
            DB::raw("IFNULL(COUNT(calls.id), 0) calls_count"),
            DB::raw("date_format(calls.created_at, '%Y-%m-%d') calls_day"),
        ])
            ->groupBy(DB::raw("calls_day"))
            ->get();
        $data = [];
        $data['labels'] = $query->unique('calls_day')->pluck('calls_day')->toArray();
        $data['lines'][] = $query->pluck('calls_count')->toArray();
        return $data;
    }

    /**
     * @param array $filters
     * @param $type
     * @return Builder
     * @throws ReflectionException
     */
    public function call_per_recommendations(array $filters, $type): Builder
    {
        $categoryType = ($type === RecommendationTypes::Test) ? Type::Laboratories : Type::Radiologies;
        $testCategoryId = Category::where('type', $categoryType)->value('id');
        $table = ($type === RecommendationTypes::Specialty) ? 'specialties' : 'tests';
        $modelName = ($type === RecommendationTypes::Test) ? 'Laboratory' : RecommendationTypes::getName($type);

        $query = $this->calls_query($filters)
            ->join('calls_recommendations', 'calls_recommendations.call_id', 'calls.id')
            ->where('calls_recommendations.recommendation_type', 'MaxDev\Models\\' . $modelName)
            ->groupBy('calls_recommendations.recommendation_id')
            ->select([DB::raw("count(*) as recommendation_count"), DB::raw("json_unquote(json_extract($table.name, '$." . app()->getLocale() . "')) as name")])
            ->orderByDesc('recommendation_count');

        return match ($type) {
            RecommendationTypes::Test, RecommendationTypes::Radiology => $query->join(
                'tests',
                function ($q) use ($testCategoryId) {
                $q->on('tests.id', '=', 'calls_recommendations.recommendation_id')->where('tests.category_id', $testCategoryId);
            }
            ),
            default => $query->join('specialties', 'specialties.id', '=', 'calls_recommendations.recommendation_id'),
        };
    }

    public function patient_missed_calls()
    {
        return Patient::join('activity_log', function ($q) {
            $q->on('users.id', '=', 'activity_log.causer_id')->where(['activity_log.causer_type' => 'MaxDev\Models\Patient', 'activity_log.log_name' => ActivitylogPatient::PatentMissedCall]);
        })
            ->leftJoin('calls', function ($q) {
                $q->on('calls.patient_id', '=', 'users.id')->where('calls.status', CallStatus::Finished);
            })
            ->groupBy('users.id')
            ->select([DB::raw('DISTINCT users.id'), 'users.fullname as name', 'users.mobile as mobile', 'activity_log.created_at as missed_call_at', DB::raw('COUNT(calls.id) as calls_count')])
            ->having('calls_count', '<', 1)
            ->get();
    }

    public function calls_no_recommendation($filters): int
    {
        return $this->calls_query($filters)->withCount([
            'callRecommendations' => function ($q) {
                $q->whereNotIn('recommendation_type', ['MaxDev\Models\Radiology', 'MaxDev\Models\Laboratory']);
            }
        ])->having('call_recommendations_count', '<', 1)->count();
    }

    public function fawryPayments($filters=[]): int
    {
        return Transaction::where('transactions.payment_method', PaymentMethod::Fawry)
            ->where('transactions.payment_status', PaymentStatus::Paid)
            ->sum('transactions.amount');
    }
}
