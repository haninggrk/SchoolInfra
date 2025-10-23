<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Shared\Models\Room;
use Modules\Shared\Models\InventoryItem;
use Illuminate\Support\Facades\DB;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Read monitoring CSV to get list of barcodes that should be monitored
        $monitoredBarcodes = $this->getMonitoredBarcodes();
        
        // Read and process inventory CSV
        $inventoryFile = public_path('inventaris.csv');
        
        if (!file_exists($inventoryFile)) {
            $this->command->error('inventaris.csv file not found in public directory');
            return;
        }
        
        $file = fopen($inventoryFile, 'r');
        $header = fgetcsv($file); // Skip header row
        
        $roomCache = [];
        $processedCount = 0;
        
        DB::beginTransaction();
        
        try {
            while (($row = fgetcsv($file)) !== false) {
                // Skip empty rows
                if (empty($row[0]) || empty($row[5])) { // Skip if room name or barcode is empty
                    continue;
                }
                
                $roomName = trim($row[0]);
                $roomCode = trim($row[1]);
                $itemCategory = trim($row[2]);
                $itemType = trim($row[3]);
                $itemName = trim($row[4]);
                $barcode = trim($row[5]);
                $qty = (int) $row[6];
                $unit = trim($row[7]);
                $dateAdded = $this->parseDate($row[8]);
                $barcodeStatus = strtolower(trim($row[9])) === 'active' ? 'active' : 'inactive';
                $status = $this->normalizeStatus($row[10]);
                $prefix = trim($row[11]);
                
                // Get or create room
                if (!isset($roomCache[$roomCode])) {
                    $room = Room::firstOrCreate(
                        ['code' => $roomCode],
                        [
                            'name' => $roomName,
                            'description' => null,
                            'floor' => null,
                            'building' => null,
                            'is_active' => true,
                        ]
                    );
                    $roomCache[$roomCode] = $room->id;
                }
                
                $roomId = $roomCache[$roomCode];
                
                // Check if this item should be monitored
                $isMonitored = in_array($barcode, $monitoredBarcodes);
                
                // Use updateOrCreate to handle duplicate barcodes
                // If barcode exists, we'll update it; otherwise create new
                InventoryItem::updateOrCreate(
                    ['barcode' => $barcode],
                    [
                        'room_id' => $roomId,
                        'item_category' => $itemCategory,
                        'item_type' => $itemType,
                        'item_name' => $itemName,
                        'prefix' => $prefix,
                        'qty' => $qty,
                        'unit' => $unit,
                        'date_added' => $dateAdded,
                        'barcode_status' => $barcodeStatus,
                        'status' => $status,
                        'is_monitored' => $isMonitored,
                        'notes' => null,
                    ]
                );
                
                $processedCount++;
                
                if ($processedCount % 100 === 0) {
                    $this->command->info("Processed {$processedCount} items...");
                }
            }
            
            DB::commit();
            fclose($file);
            
            $this->command->info("Successfully imported {$processedCount} inventory items");
            $this->command->info("Monitored items: " . count($monitoredBarcodes));
            
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($file);
            $this->command->error('Error importing data: ' . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Get list of barcodes that should be monitored from monitoring.csv
     */
    private function getMonitoredBarcodes(): array
    {
        $monitoringFile = public_path('monitoring.csv');
        
        if (!file_exists($monitoringFile)) {
            $this->command->warn('monitoring.csv file not found, no items will be marked as monitored');
            return [];
        }
        
        $barcodes = [];
        $file = fopen($monitoringFile, 'r');
        
        // Skip first row (school name) and header row
        fgetcsv($file);
        fgetcsv($file);
        
        while (($row = fgetcsv($file)) !== false) {
            if (!empty($row[5])) { // Barcode column
                $barcodes[] = trim($row[5]);
            }
        }
        
        fclose($file);
        
        return array_unique($barcodes);
    }
    
    /**
     * Parse date from various formats
     */
    private function parseDate(string $date): ?string
    {
        if (empty($date)) {
            return now()->format('Y-m-d');
        }
        
        try {
            $timestamp = strtotime($date);
            return $timestamp ? date('Y-m-d', $timestamp) : now()->format('Y-m-d');
        } catch (\Exception $e) {
            return now()->format('Y-m-d');
        }
    }
    
    /**
     * Normalize status from CSV to database enum values
     */
    private function normalizeStatus(string $status): string
    {
        $status = strtolower(trim($status));
        
        $statusMap = [
            'baik' => 'baik',
            'rusak' => 'rusak',
            'dalam perbaikan' => 'dalam_perbaikan',
            'dalam_perbaikan' => 'dalam_perbaikan',
        ];
        
        return $statusMap[$status] ?? 'baik';
    }
}
