<?php

namespace App\Services;

use App\Models\Application;
use App\Repositories\ApplicationRepository;
use Illuminate\Support\Facades\Validator;


class ApplicationService
{
    protected ApplicationRepository $applicationRepository;
    protected Application $application;

    public function __construct(ApplicationRepository $applicationRepository, Application $application)
    {
        $this->applicationRepository = $applicationRepository;
        $this->application = $application;
    }

    public function getApplicationAll()
    {
        return Application::all();
    }

    public function getFilterApplicationAndStatus(array $filters)
    {
        $query = Application::query();

        if (isset($filters['start_date'])) {
            $query->where('created_at', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('created_at', '<=', $filters['end_date']);
        }

        if (isset($filters['status'])) {
            $validateFilters = array_keys(Application::statusLabels());
            if (in_array($filters['status'], $validateFilters)) {
                $query->where('status', '=', $filters['status']);
            }
        }

        return $query;
    }

    public function validateApplication(array $data): \Illuminate\Contracts\Validation\Validator
    {
        return Validator::make($data, [
            'name' => 'required|string',
            'email' => 'required|string|unique:applications,email',
            'message' => 'required|string',
            'comment' => 'nullable|string'
        ], [
            'required' => ':attribute - Обятельное поле',
            'string' => ':attribute - Должно быть строкой',
            'email' => ':attribute - Должен быть email',
            'nullable' => ':attribute - Необязательное поле',
            'unique' => ':attribute - Должен быть уникальным',
        ]);
    }

    public function createApplication(array $data)
    {
        $validator = $this->validateApplication($data);

        if ($validator->fails()) {
            return [
                'message' => 'Данные заполены некорректно',
                $validator->errors()->toArray()
            ];
        }

        $result = Application::query()->create($data);

        if (!$result) {
            return 'При попытке сохранить данные произошла ошибка';
        }

        return $result;
    }

    public function respondToApplication($id, array $data)
    {
        $application = Application::find($id);

        if (!$application) {
            return [
                'message' => 'Заявка не найдена'
            ];
        }

        $application->update(['status' => Application::STATUS_CONFIRMED, 'comment' => $data['comment']]);

        return $application;
    }
}
