<?php

namespace App\Http\Controllers;

use App\Models\Event;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use Illuminate\Support\Facades\Response;

class ReportExportController extends Controller
{
    public function export($id, $format)
    {
        $event = Event::with(['users', 'tickets', 'sub_events'])->findOrFail($id);

    $spreadsheet = new Spreadsheet();

    // Aba 1: Participantes
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Participantes');
    $sheet->setCellValue('A1', 'Nome');
    $sheet->setCellValue('B1', 'Email');
    $sheet->setCellValue('C1', 'Ticket');

    $row = 2;
    foreach ($event->users as $user) {
        $sheet->setCellValue("A{$row}", $user->name);
        $sheet->setCellValue("B{$row}", $user->email);
        $ticket = $event->tickets->firstWhere('id', $user->pivot->ticket_id);
        $sheet->setCellValue("C{$row}", $ticket ? $ticket->title : 'Não selecionado');
        $row++;
    }

    // Aba 2: Sub-Eventos
    $subeventSheet = $spreadsheet->createSheet();
    $subeventSheet->setTitle('Subeventos');
    $subeventSheet->setCellValue('A1', 'Título');
    $subeventSheet->setCellValue('B1', 'Descrição');
    $subeventSheet->setCellValue('C1', 'Participantes');

    $row = 2;
    foreach ($event->sub_events as $subevent) {
        $subeventSheet->setCellValue("A{$row}", $subevent->title);
        $subeventSheet->setCellValue("B{$row}", $subevent->description);
        $subeventSheet->setCellValue("C{$row}", $subevent->users->count());
        $row++;
    }

    $row = 2;
    foreach($event->sub_events as $subevent){
        $subeventSheet = $spreadsheet->createSheet();
        $subeventSheet->setTitle($subevent->title);
        $subeventSheet->setCellValue('A1', 'Nome');
        $subeventSheet->setCellValue('B1', 'Email');

        foreach($subevent->users as $user){
            $subeventSheet->setCellValue("A{$row}", $user->email);
            $subeventSheet->setCellValue("B{$row}", $user->name);
            $row++;
        }
    }

    // Aba 3: Tickets
    $ticketSheet = $spreadsheet->createSheet();
    $ticketSheet->setTitle('Tickets');
    $ticketSheet->setCellValue('A1', 'Título');
    $ticketSheet->setCellValue('B1', 'Descrição');
    $ticketSheet->setCellValue('C1', 'Quantidade Disponível');
    $ticketSheet->setCellValue('D1', 'Quantidade Vendida');
    $ticketSheet->setCellValue('E1', 'Total Arrecadado');

    $row = 2;
    foreach ($event->tickets as $ticket) {
        $soldCount = $event->users->where('pivot.ticket_id', $ticket->id)->count();
        $ticketSheet->setCellValue("A{$row}", $ticket->title);
        $ticketSheet->setCellValue("B{$row}", $ticket->description);
        $ticketSheet->setCellValue("C{$row}", $ticket->quantity);
        $ticketSheet->setCellValue("D{$row}", $soldCount);
        $ticketSheet->setCellValue("E{$row}", $soldCount * $ticket->price);
        $row++;
    }

        // Exportar para o formato selecionado
        $fileName = "relatorio_evento_{$id}.{$format}";
        if ($format === 'xlsx') {
            $writer = new Xlsx($spreadsheet);
            $contentType = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        } elseif ($format === 'csv') {
            $writer = new Csv($spreadsheet);
            $contentType = 'text/csv';
        } else {
            abort(404, 'Formato não suportado.');
        }

        // Salvar em memória e retornar o download
        $tempFile = tempnam(sys_get_temp_dir(), 'export');
        $writer->save($tempFile);

        return Response::download($tempFile, $fileName, [
            'Content-Type' => $contentType,
        ])->deleteFileAfterSend(true);
    }
}
