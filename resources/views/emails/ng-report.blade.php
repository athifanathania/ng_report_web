<x-mail::message>
# Pemberitahuan Temuan Part NG

Halo Team **{{ $report->supplier->name }}**,

Kami menginformasikan bahwa terdapat temuan part yang tidak sesuai (NG) dengan detail sebagai berikut:

<x-mail::table>
| Item | Detail |
| :--- | :--- |
| **Part No** | {{ $report->part->part_no }} |
| **Nama Part** | {{ $report->part->part_name }} |
| **Kategori NG** | {{ $report->ng_category }} |
| **Detail Temuan** | {{ $report->ng_detail }} |
| **Tanggal Temuan** | {{ $report->input_date->format('d F Y') }} |
</x-mail::table>

> **Catatan:** Bukti foto temuan telah kami lampirkan bersama email ini (lihat bagian attachment).

Mohon untuk segera ditindaklanjuti. Terima kasih.

Salam,  
**Indomatsumoto Quality Team**
</x-mail::message>