<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TicketCategory; // Ensure your model name is correct
use Illuminate\Support\Str;

class TicketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Order Issue',
                'sort_order' => 1,
            ],
            [
                'name' => 'Payment Issue',
                'sort_order' => 2,
            ],
            [
                'name' => 'Refill Request',
                'sort_order' => 3,
            ],
            [
                'name' => 'Cancellation Request',
                'sort_order' => 4,
            ],
            [
                'name' => 'Child Panel Support',
                'sort_order' => 5,
            ],
            [
                'name' => 'API Integration',
                'sort_order' => 6,
            ],
            [
                'name' => 'Other / General',
                'sort_order' => 7,
            ],
        ];

        foreach ($categories as $category) {
            TicketCategory::updateOrCreate(
                ['slug' => Str::slug($category['name'])],
                [
                    'name' => $category['name'],
                    'is_active' => true,
                    'sort_order' => $category['sort_order']
                ]
            );
        }
    }
}