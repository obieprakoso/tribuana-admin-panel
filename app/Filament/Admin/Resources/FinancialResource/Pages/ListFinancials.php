<?php

namespace App\Filament\Admin\Resources\FinancialResource\Pages;

use App\Filament\Admin\Resources\FinancialResource;
use App\Filament\Admin\Resources\FinancialResource\Widgets\FinancialSummary;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Resident;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use App\Models\TransactionType;

class ListFinancials extends ListRecords
{
    protected static string $resource = FinancialResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            FinancialSummary::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('downloadReport')
                ->label('Download Report')
                ->icon('heroicon-o-document-arrow-down')
                ->form([
                    Select::make('report_type')
                        ->label('Tipe Laporan')
                        ->options([
                            'summary' => 'Ringkasan',
                            'resident' => 'Per Penduduk',
                        ])
                        ->required(),
                    DatePicker::make('start_date')
                        ->label('Tanggal Mulai')
                        ->required(),
                    DatePicker::make('end_date')
                        ->label('Tanggal Akhir')
                        ->required(),
                    Select::make('transaction_types')
                        ->label('Tipe Transaksi')
                        ->options(TransactionType::pluck('name', 'id'))
                        ->multiple()
                        ->preload()
                        ->searchable()
                        ->required(),
                ])
                ->action(function (array $data) {
                    $query = $this->getResource()::getModel()::query()
                        ->whereBetween('created_at', [
                            $data['start_date'],
                            $data['end_date'] . ' 23:59:59'
                        ]);

                    $transactions = $query->with(['resident', 'transactionType'])->get();
                    $selectedTypes = TransactionType::whereIn('id', $data['transaction_types'])
                        ->orderBy('name')
                        ->get();

                    if ($data['report_type'] === 'summary') {
                        // Generate summary report
                        $summaryData = [];
                        $grandTotal = 0;
                        $totalIn = 0;
                        $totalOut = 0;
                        $totalInFromResidents = 0;
                        
                        foreach ($selectedTypes as $type) {
                            $typeTransactions = $transactions->where('transaction_type_id', $type->id);
                            $total = $typeTransactions->sum('amount');
                            $grandTotal += $total;
                            
                            // Calculate in/out totals
                            $inAmount = $typeTransactions->where('type', 'in')->sum('amount');
                            $outAmount = $typeTransactions->where('type', 'out')->sum('amount');
                            
                            // Calculate money in from residents
                            $inFromResidents = $typeTransactions
                                ->where('type', 'in')
                                ->filter(function ($transaction) {
                                    return $transaction->resident_id !== null;
                                })
                                ->sum('amount');
                            
                            $totalIn += $inAmount;
                            $totalOut += $outAmount;
                            $totalInFromResidents += $inFromResidents;
                            
                            $summaryData[] = [
                                'name' => $type->name,
                                'total' => number_format($total, 0, ',', '.'),
                                'raw_total' => $total,
                                'in_amount' => $inAmount,
                                'out_amount' => $outAmount,
                                'in_from_residents' => $inFromResidents,
                            ];
                        }

                        $pdf = PDF::loadView('reports.financial-summary', [
                            'data' => $summaryData,
                            'date' => now()->format('d F Y'),
                            'start_date' => \Carbon\Carbon::parse($data['start_date'])->format('d F Y'),
                            'end_date' => \Carbon\Carbon::parse($data['end_date'])->format('d F Y'),
                            'grand_total' => number_format($grandTotal, 0, ',', '.'),
                            'total_in' => number_format($totalIn, 0, ',', '.'),
                            'total_out' => number_format($totalOut, 0, ',', '.'),
                            'total_in_from_residents' => number_format($totalInFromResidents, 0, ',', '.'),
                            'balance' => number_format($totalIn - $totalOut, 0, ',', '.'),
                        ]);
                    } else {
                        // Generate detailed resident report
                        $reportData = [];
                        $residents = Resident::where('is_deleted', false)->get();
                        
                        foreach ($residents as $resident) {
                            $residentTransactions = $transactions->where('resident_id', $resident->id);
                            
                            $row = [
                                'name' => $resident->head_of_family,
                                'house_number' => $resident->house_number,
                            ];

                            foreach ($data['transaction_types'] as $typeId) {
                                $type = TransactionType::find($typeId);
                                $row[$type->id] = number_format(
                                    $residentTransactions->where('transaction_type_id', $typeId)->sum('amount'),
                                    0, ',', '.'
                                );
                            }
                            
                            $reportData[] = $row;
                        }

                        $pdf = PDF::loadView('reports.financial', [
                            'data' => $reportData,
                            'date' => now()->format('d F Y'),
                            'start_date' => \Carbon\Carbon::parse($data['start_date'])->format('d F Y'),
                            'end_date' => \Carbon\Carbon::parse($data['end_date'])->format('d F Y'),
                            'transaction_types' => $selectedTypes,
                        ]);
                    }

                    return response()->streamDownload(function () use ($pdf) {
                        echo $pdf->output();
                    }, 'financial-report-' . now()->format('Y-m-d') . '.pdf');
                }),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua Transaksi'),
            'in' => Tab::make('Uang Masuk')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'in')),
            'out' => Tab::make('Uang Keluar')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('type', 'out')),
        ];
    }

    protected function getTotalAmount(?string $type = null): float
    {
        $query = $this->getResource()::getModel()::query();
        
        if ($type) {
            $query->where('type', $type);
        }

        // Menggunakan query yang sama dengan filter yang aktif
        $query = $this->getFilteredTableQuery();
        
        return $query->sum('amount');
    }

    public function getFilteredTableQuery(): Builder
    {
        $query = parent::getFilteredTableQuery();
        
        // Mengambil filter yang aktif
        $filters = $this->getTableFilters();
        
        foreach ($filters as $filter) {
            if ($filter->isActive()) {
                $query = $filter->apply($query);
            }
        }
        
        return $query;
    }
} 