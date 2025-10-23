# Inventory Seeder Documentation

## Overview
The inventory seeder automatically imports data from two CSV files:
- `public/inventaris.csv` - Complete inventory list (1261 items)
- `public/monitoring.csv` - Subset of items to be monitored (214 items)

## Database Structure

### New Migration: `add_is_monitored_to_inventory_items_table`
Adds `is_monitored` boolean field to `inventory_items` table to mark items that need monitoring.

### Updated Model: `InventoryItem`
- Added `is_monitored` to `$fillable` and `$casts`
- Updated `scopeDigital()` to filter by `is_monitored = true` instead of category
- Added new `scopeMonitored()` for consistency

## How It Works

1. **InventorySeeder** reads `monitoring.csv` first to get list of barcodes that should be monitored
2. Then reads `inventaris.csv` and imports all items
3. For each item, checks if its barcode is in the monitoring list
4. Uses `updateOrCreate()` to handle duplicate barcodes (keeps the last occurrence)
5. Auto-creates rooms based on room codes from CSV

## CSV Format

### inventaris.csv
```
Room Name,Room Code,Item Category,Item Type,Item Name,Barcode,Qty,Unit,Date Added,Barcode Status,Status,Prefix
```

### monitoring.csv
```
Room Name,Room Code,Item Category,Item Type,Item Name,Barcode,Qty,Unit,Date Added,Barcode Status,Status,Prefix
```

Both use the same format, but `monitoring.csv` contains only digital/electronic items.

## Usage

### Run Seeder
```bash
php artisan db:seed --class=InventorySeeder
```

### Run All Seeds (Fresh)
```bash
php artisan migrate:fresh --seed
```

This will:
- Create 2 users (admin@school.com / guru@school.com, password: `password`)
- Import all 1261 inventory items
- Mark 214 items as monitored
- Create rooms automatically

## Statistics from Current Import
- **Total Items**: 1,261
- **Monitored Items**: 214 (17%)
- **Rooms Created**: Auto-generated from CSV data
- **Duplicate Barcodes**: Handled automatically (last entry wins)

## Monitoring Feature

Items marked with `is_monitored = true` will:
- Appear in the **Monitoring Sarana & Prasarana** module
- Show up in filtered views for digital assets
- Be available for damage reporting focused on digital equipment

Non-monitored items (furniture, books, etc.) will:
- Only appear in the **Daftar Inventaris** (full inventory list)
- Still be available for all CRUD operations
- Can be toggled to monitored status via admin panel

## Status Mapping

The seeder normalizes status from CSV:
- `Baik` → `baik`
- `Rusak` → `rusak`
- `Dalam Perbaikan` / `Dalam_perbaikan` → `dalam_perbaikan`

## Barcode Status
- `Active` → `active`
- `Inactive` → `inactive`

## Future Enhancements

1. Add UI toggle in InventarisForm to mark/unmark items as monitored
2. Add filter in InventarisIndex to show only monitored items
3. Add bulk action to mark multiple items as monitored
4. Add monitoring statistics dashboard

