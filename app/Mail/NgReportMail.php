<?php

namespace App\Mail;

use App\Models\NgReport;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Support\Facades\Storage;

class NgReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public NgReport $report) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan Part NG - ' . $this->report->part->part_name,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ng-report', 
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        // Cek apakah ada foto yang diupload
        if ($this->report->photos && is_array($this->report->photos)) {
            foreach ($this->report->photos as $photo) {
                // Mengambil path lengkap file dari disk public
                $path = storage_path('app/public/' . $photo);

                // Pastikan filenya benar-benar ada sebelum dilampirkan
                if (file_exists($path)) {
                    $attachments[] = Attachment::fromPath($path);
                }
            }
        }

        return $attachments;
    }
}