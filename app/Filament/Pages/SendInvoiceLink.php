<?php

namespace App\Filament\Pages;

use App\Models\Invoice;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class SendInvoiceLink extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-paper-airplane';
    protected static ?string $title = 'Send Invoice Link';
        protected static ?string $navigationGroup = 'Invoice';


    // Link to your blade: resources/views/filament/pages/send-invoice-link.blade.php
    protected static string $view = 'filament.pages.send-invoice-link';

    public $service;
    public $package;
    public $amount;
    public $email;

    public function mount(): void
    {
        $this->form->fill();
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('service')
                ->label('Service')
                ->options([
                    'Training' => 'Training',
                    'Speaking' => 'Speaking',
                    'Community' => 'Community',
                    'Content' => 'Content',
                    'Course' => 'Course',
                    'Podcast' => 'Podcast',
                    'Consulting' => 'Consulting',
                    'Audit' => 'Audit',
                ])
                ->required(),

            Forms\Components\TextInput::make('package')
                ->label('Package Name')
                ->required(),

            Forms\Components\TextInput::make('amount')
                ->label('Amount (BDT)')
                ->numeric()
                ->required(),

            Forms\Components\TextInput::make('email')
                ->label('User Email')
                ->email()
                ->required(),
        ];
    }

    // You can keep this or remove it if you're using a custom form button in the blade
    protected function getFormActions(): array
    {
        return [];
    }
    public $success = false;

    public function submit()
    {
        $invoiceCode = strtoupper(Str::random(6));
        $fullPackage = "{$this->package}-INVOICE-{$invoiceCode}";
        

        // $url = url("/order?service={$this->service}&package={$fullPackage}&amount={$this->amount}");
        // Hardcoded React frontend base URL
        $orderPage = 'https://passionateworlduniversity.com';  // <--- put your React site URL here

        $url = $orderPage . '/order?' . http_build_query([
            'service' => $this->service,
            'package' => $fullPackage,
            'amount' => $this->amount,
        ]);

        Invoice::create([
            'service' => $this->service,
            'package' => $fullPackage,
            'amount' => $this->amount,
            'email' => $this->email,
            'invoice_code' => $invoiceCode,
            'status' => 'unpaid',
        ]);

        $emailBody = <<<EOT
Hello,

You’ve been issued an invoice for the following service:

Service: {$this->service}
Package: {$this->package}
Amount: ৳{$this->amount} BDT

To complete your payment, please click the link below:
{$url}

If you have any questions, feel free to reply to this email.

Thank you for choosing our service.

—
Best regards,  
Your Company Name
EOT;

        Mail::raw($emailBody, function ($message) {
            $message->to($this->email)
                ->subject('Invoice Link for Your Selected Service');
        });

        Notification::make()
            ->title("Invoice sent to {$this->email}")
            ->body("Payment link has been sent successfully.")
            ->success()
            ->send();

        // ✅ Set success to true so that blade message shows
        $this->success = true;

        $this->form->fill([
            'service' => null,
            'package' => null,
            'amount' => null,
            'email' => null,
        ]);
    }

}
