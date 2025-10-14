<?php

namespace App\Exports\Sheets;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class UsersListsSheet implements FromView, WithTitle, ShouldAutoSize, WithEvents
{
    private $users;
    private $label;

    public function __construct($users, $label)
    {
        $this->users = $users;
        $this->label = $label;
    }

    public function view(): View
    {
        return view('back.exports.users', [
            'users'      => $this->users,
            'request'    => request(),
        ]);
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->label;
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event){
                $event->getDelegate()->setRightToLeft(true);
            },
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getDelegate()->getStyle('A:F')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
