<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'total_amount' => (float)$this->total_amount,
            'paid_amount' => (float)$this->paid_amount,
            
            // Calculating balance directly in the API response
            'balance' => (float)($this->total_amount - $this->paid_amount),
            
            'status' => $this->status,

            'due_date' => $this->due_date ? $this->due_date->format('Y-m-d') : null,

            // STUDENT RELATIONSHIP
            'student' => $this->whenLoaded('student', function () {
                return [
                    'id' => $this->student->id,
                    'name' => $this->student->name,
                    'admission_number' => $this->student->studentProfile?->admission_number,
                ];
            }),

            // TERM RELATIONSHIP
            'term' => $this->whenLoaded('term', function () {
                return [
                    'id' => $this->term->id,
                    'name' => $this->term->name,
                ];
            }),

            // ACADEMIC SESSION RELATIONSHIP
            'academic_session' => $this->whenLoaded('academicSession', function () {
                return [
                    'id' => $this->academicSession->id,
                    'name' => $this->academicSession->name,
                ];
            }),

            // INVOICE ITEMS 
            'items' => $this->whenLoaded('items', function () {
                return $this->items->map(function ($item) {
                    return [
                        'description' => $item->description,
                        'amount' => (float)$item->amount,
                        'quantity' => $item->quantity ?? 1, 
                        'total' => (float)($item->total ?? ($item->amount * ($item->quantity ?? 1))),
                    ];
                });
            }),

            // PAYMENTS HISTORY
            'payments' => $this->whenLoaded('payments', function () {
                return $this->payments->map(function ($payment) {
                    return [
                        'amount' => (float)$payment->amount,
                        'method' => $payment->method,
                        'reference' => $payment->reference,
                        'paid_at' => $payment->created_at->format('Y-m-d H:i:s'),
                    ];
                });
            }),

            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
    
}
