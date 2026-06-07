<?php

namespace App\Http\Controllers;

use App\Http\Resources\ResumeResource;
use App\Models\SiteSetting;
use App\Services\Resume\ResumeBuilder;
use Illuminate\Http\JsonResponse;

class ResumeJsonController extends Controller
{
    public function __construct(private ResumeBuilder $builder) {}

    public function __invoke(): JsonResponse
    {
        $locale = app()->getLocale();
        $template = SiteSetting::instance()->resume_template ?? 'default';

        $data = $this->builder->build($locale, $template);

        return (new ResumeResource($data))
            ->response()
            ->header('Content-Disposition', 'inline; filename="resume-'.$locale.'.json"');
    }
}
