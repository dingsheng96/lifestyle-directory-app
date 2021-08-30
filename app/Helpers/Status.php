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

    public function publishStatus(): array
    {
        return [
            'publish' => __('labels.publish'),
            'draft' => __('labels.draft')
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
            'rejected' => [
                'text' => __('labels.rejected'),
                'class' => 'badge badge-pill badge-lg badge-danger'
            ],
            'approved' => [
                'text' => __('labels.approved'),
                'class' => 'badge badge-pill badge-lg badge-success'
            ],
            'publish' => [
                'text' => __('labels.publish'),
                'class' => 'badge badge-pill badge-lg bg-purple'
            ],
            'draft' => [
                'text' => __('labels.draft'),
                'class' => 'badge badge-pill badge-lg badge-primary'
            ]
        ];

        return $labels[$status];
    }
}
