<?php

namespace App\Filament\Resources\CommunityPendingOrderResource\Pages;

use App\Filament\Resources\CommunityPendingOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Notifications\OrderSuccessNotification;


class EditCommunityPendingOrder extends EditRecord
{
    protected static string $resource = CommunityPendingOrderResource::class;

     protected function afterSave(): void
    {
        $order = $this->record;

        // ✅ Only send email if status was set to "completed"
        if ($order->status === 'completed') {
            $order->customer?->notify(new OrderSuccessNotification($order));
        }
    }


    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
