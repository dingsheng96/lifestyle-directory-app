<?php

namespace App\Helpers;

class Status
{
    public function activeStatus(): array
    {
        return [
            'active'    =>  __('labels.active'),
            'inactive'  =>  __('labels.inactive')
        ];
    }

    public function statusLabel(string $status): array
    {
        $labels = [
            'active' => [
                'text' => __('labels.active'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'inactive' => [
                'text' => __('labels.inactive'),
                'class' => 'badge badge-pill badge-lg badge-danger'
            ],
            'success' => [
                'text' => __('labels.success'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'pending' => [
                'text' => __('labels.pending'),
                'class' => 'badge badge-pill badge-lg badge-warning'
            ],
            'failed' => [
                'text' => __('labels.failed'),
                'class' => 'badge badge-pill badge-lg badge-danger'
            ],
            'paid' => [
                'text' => __('labels.paid'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'rejected' => [
                'text' => __('labels.rejected'),
                'class' => 'badge badge-pill badge-lg badge-danger'
            ],
            'cancelled' => [
                'text' => __('labels.cancelled'),
                'class' => 'badge badge-pill badge-lg badge-danger'
            ],
            'approved' => [
                'text' => __('labels.approved'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'published' => [
                'text' => __('labels.published'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'draft' => [
                'text' => __('labels.draft'),
                'class' => 'badge badge-pill badge-lg badge-primary'
            ],
            'expired' => [
                'text' => __('labels.expired'),
                'class' => 'badge badge-pill badge-lg badge-danger'
            ],
            'boosting' => [
                'text' => __('labels.boosting'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'upcoming' => [
                'text' => __('labels.upcoming'),
                'class' => 'badge badge-pill badge-lg badge-warning'
            ]
        ];

        return $labels[$status];
    }
}
