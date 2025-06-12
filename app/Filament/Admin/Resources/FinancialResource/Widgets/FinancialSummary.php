<?php

namespace App\Filament\Admin\Resources\FinancialResource\Widgets;

use App\Filament\Admin\Resources\FinancialResource;
use App\Models\Financial;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class FinancialSummary extends BaseWidget
{
    public function getStats(): array
    {
        $totalIn = $this->getTotalAmount('in');
        $totalOut = $this->getTotalAmount('out');
        $currentBalance = $totalIn - $totalOut;

        return [
            Stat::make('Total Transaksi', 'Rp ' . number_format($this->getTotalAmount(), 0, ',', '.'))
                ->description('Semua transaksi')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('gray'),
            Stat::make('Total Pemasukan', 'Rp ' . number_format($totalIn, 0, ',', '.'))
                ->description('Uang masuk')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            Stat::make('Total Pengeluaran', 'Rp ' . number_format($totalOut, 0, ',', '.'))
                ->description('Uang keluar')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            Stat::make('Saldo Saat Ini', 'Rp ' . number_format($currentBalance, 0, ',', '.'))
                ->description('Pemasukan - Pengeluaran')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($currentBalance >= 0 ? 'success' : 'danger'),
        ];
    }

    protected function getTotalAmount(?string $type = null): float
    {
        $query = Financial::query();
        
        if ($type) {
            $query->where('type', $type);
        }

        return $query->sum('amount');
    }
} 