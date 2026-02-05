<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\MaskapaiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\JamaahController;
use App\Http\Controllers\PaketUmrohController;
use App\Http\Controllers\PaketHajiController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\TabunganUmrohController;
use App\Http\Controllers\TabunganHajiController;
use App\Http\Controllers\SetoranUmrohController;
use App\Http\Controllers\SetoranHajiController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\PembelianProdukController;
use App\Http\Controllers\PengeluaranProdukController;
use App\Http\Controllers\PendaftaranHajiController;

use App\Http\Controllers\TransaksiLayananController;
use App\Http\Controllers\TransaksiTiketController;
use App\Http\Controllers\KeberangkatanUmrohController;
use App\Http\Controllers\CustomerUmrohController;
use App\Http\Controllers\KeberangkatanHajiController;
use App\Http\Controllers\CustomerHajiController;
use App\Http\Controllers\PendaftaranUmrohController;
use App\Http\Controllers\BonusAgentController;
use App\Http\Controllers\PembayaranUmrohController;
use App\Http\Controllers\PembayaranHajiController;
use App\Http\Controllers\PengeluaranUmrohController;
use App\Http\Controllers\PengeluaranHajiController;
use App\Http\Controllers\PengeluaranUmumController;
use App\Http\Controllers\PemasukanUmumController;
use App\Http\Controllers\SuratRekomendasiController;
use App\Http\Controllers\SuratIzinCutiController;
use App\Http\Controllers\LaporanUmrohController;
use App\Http\Controllers\LaporanHajiController;
use App\Http\Controllers\PembayaranTiketController;
use App\Http\Controllers\PembayaranLayananController;
use App\Http\Controllers\LaporanKeuanganController;
use App\Http\Controllers\RugiLabaPenjualanController;
use App\Http\Controllers\ProfileController;




// Authentication Routes
use App\Http\Controllers\AuthController;
Route::get('/signin', [AuthController::class, 'login'])->name('login');
Route::post('/signin', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/signup', function () {
    return view('pages.auth.signup', ['title' => 'Sign Up']);
})->name('signup');

Route::middleware('auth')->group(function () {

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

Route::get('/data-hotel', [HotelController::class, 'index'])->name('data-hotel');
Route::get('/data-hotel/create', [HotelController::class, 'create'])->name('data-hotel.create');
Route::post('/data-hotel', [HotelController::class, 'store'])->name('data-hotel.store');
Route::get('/data-hotel/export', [HotelController::class, 'export'])->name('data-hotel.export');
Route::get('/data-hotel/print', [HotelController::class, 'printData'])->name('data-hotel.print');
Route::get('/data-hotel/{id}/edit', [HotelController::class, 'edit'])->name('data-hotel.edit');
Route::put('/data-hotel/{id}', [HotelController::class, 'update'])->name('data-hotel.update');
Route::delete('/data-hotel/{id}', [HotelController::class, 'destroy'])->name('data-hotel.destroy');
Route::get('/data-hotel/{id}', [HotelController::class, 'show'])->name('data-hotel.show');

Route::get('/data-maskapai', [MaskapaiController::class, 'index'])->name('data-maskapai');
Route::get('/data-maskapai/create', [MaskapaiController::class, 'create'])->name('data-maskapai.create');
Route::post('/data-maskapai', [MaskapaiController::class, 'store'])->name('data-maskapai.store');
Route::get('/data-maskapai/export', [MaskapaiController::class, 'export'])->name('data-maskapai.export');
Route::get('/data-maskapai/print', [MaskapaiController::class, 'printData'])->name('data-maskapai.print');
Route::get('/data-maskapai/{id}/edit', [MaskapaiController::class, 'edit'])->name('data-maskapai.edit');
Route::put('/data-maskapai/{id}', [MaskapaiController::class, 'update'])->name('data-maskapai.update');
Route::delete('/data-maskapai/{id}', [MaskapaiController::class, 'destroy'])->name('data-maskapai.destroy');
Route::get('/data-maskapai/{id}', [MaskapaiController::class, 'show'])->name('data-maskapai.show');

Route::get('/data-karyawan', [KaryawanController::class, 'index'])->name('data-karyawan');
Route::get('/data-karyawan/create', [KaryawanController::class, 'create'])->name('data-karyawan.create');
Route::post('/data-karyawan', [KaryawanController::class, 'store'])->name('data-karyawan.store');
Route::get('/data-karyawan/export', [KaryawanController::class, 'export'])->name('data-karyawan.export');
Route::get('/data-karyawan/print', [KaryawanController::class, 'printData'])->name('data-karyawan.print');
Route::get('/data-karyawan/{id}/edit', [KaryawanController::class, 'edit'])->name('data-karyawan.edit');
Route::put('/data-karyawan/{id}', [KaryawanController::class, 'update'])->name('data-karyawan.update');
Route::delete('/data-karyawan/{id}', [KaryawanController::class, 'destroy'])->name('data-karyawan.destroy');
Route::get('/data-karyawan/{id}', [KaryawanController::class, 'show'])->name('data-karyawan.show');

Route::get('/data-agent', [AgentController::class, 'index'])->name('data-agent');
Route::get('/data-agent/create', [AgentController::class, 'create'])->name('data-agent.create');
Route::post('/data-agent', [AgentController::class, 'store'])->name('data-agent.store');
Route::get('/data-agent/export', [AgentController::class, 'export'])->name('data-agent.export');
Route::get('/data-agent/print', [AgentController::class, 'printData'])->name('data-agent.print');
Route::get('/data-agent/{id}/edit', [AgentController::class, 'edit'])->name('data-agent.edit');
Route::put('/data-agent/{id}', [AgentController::class, 'update'])->name('data-agent.update');
Route::delete('/data-agent/{id}', [AgentController::class, 'destroy'])->name('data-agent.destroy');
Route::get('/data-agent/{id}', [AgentController::class, 'show'])->name('data-agent.show');

Route::get('/data-pelanggan', [PelangganController::class, 'index'])->name('data-pelanggan');
Route::get('/data-pelanggan/create', [PelangganController::class, 'create'])->name('data-pelanggan.create');
Route::post('/data-pelanggan', [PelangganController::class, 'store'])->name('data-pelanggan.store');
Route::get('/data-pelanggan/print', [PelangganController::class, 'printData'])->name('data-pelanggan.print');
Route::get('/data-pelanggan/{id}/edit', [PelangganController::class, 'edit'])->name('data-pelanggan.edit');
Route::put('/data-pelanggan/{id}', [PelangganController::class, 'update'])->name('data-pelanggan.update');
Route::delete('/data-pelanggan/{id}', [PelangganController::class, 'destroy'])->name('data-pelanggan.destroy');
Route::get('/data-pelanggan/{id}', [PelangganController::class, 'show'])->name('data-pelanggan.show');

Route::get('/data-layanan', [LayananController::class, 'index'])->name('data-layanan');
Route::get('/data-layanan/create', [LayananController::class, 'create'])->name('data-layanan.create');
Route::post('/data-layanan', [LayananController::class, 'store'])->name('data-layanan.store');
Route::get('/data-layanan/print', [LayananController::class, 'printData'])->name('data-layanan.print');
Route::get('/data-layanan/{id}/edit', [LayananController::class, 'edit'])->name('data-layanan.edit');
Route::put('/data-layanan/{id}', [LayananController::class, 'update'])->name('data-layanan.update');
Route::delete('/data-layanan/{id}', [LayananController::class, 'destroy'])->name('data-layanan.destroy');
Route::get('/data-layanan/{id}', [LayananController::class, 'show'])->name('data-layanan.show');

Route::get('/data-produk', [ProdukController::class, 'index'])->name('data-produk');
Route::get('/data-produk/create', [ProdukController::class, 'create'])->name('data-produk.create');
Route::post('/data-produk', [ProdukController::class, 'store'])->name('data-produk.store');
Route::get('/data-produk/print', [ProdukController::class, 'printData'])->name('data-produk.print');
Route::get('/data-produk/{id}/edit', [ProdukController::class, 'edit'])->name('data-produk.edit');
Route::put('/data-produk/{id}', [ProdukController::class, 'update'])->name('data-produk.update');
Route::delete('/data-produk/{id}', [ProdukController::class, 'destroy'])->name('data-produk.destroy');
Route::get('/data-produk/{id}', [ProdukController::class, 'show'])->name('data-produk.show');

Route::get('/data-supplier', [SupplierController::class, 'index'])->name('data-supplier');
Route::get('/data-supplier/create', [SupplierController::class, 'create'])->name('data-supplier.create');
Route::post('/data-supplier', [SupplierController::class, 'store'])->name('data-supplier.store');
Route::get('/data-supplier/print', [SupplierController::class, 'printData'])->name('data-supplier.print');
Route::get('/data-supplier/{id}/edit', [SupplierController::class, 'edit'])->name('data-supplier.edit');
Route::put('/data-supplier/{id}', [SupplierController::class, 'update'])->name('data-supplier.update');
Route::delete('/data-supplier/{id}', [SupplierController::class, 'destroy'])->name('data-supplier.destroy');
Route::get('/data-supplier/{id}', [SupplierController::class, 'show'])->name('data-supplier.show');

Route::get('/data-jamaah', [JamaahController::class, 'index'])->name('data-jamaah');
Route::get('/data-jamaah/create', [JamaahController::class, 'create'])->name('data-jamaah.create');
Route::post('/data-jamaah', [JamaahController::class, 'store'])->name('data-jamaah.store');
Route::get('/data-jamaah/print', [JamaahController::class, 'printData'])->name('data-jamaah.print');
Route::get('/data-jamaah/export', [JamaahController::class, 'exportData'])->name('data-jamaah.export');
Route::get('/data-jamaah/{id}/edit', [JamaahController::class, 'edit'])->name('data-jamaah.edit');
Route::put('/data-jamaah/{id}', [JamaahController::class, 'update'])->name('data-jamaah.update');
Route::delete('/data-jamaah/{id}', [JamaahController::class, 'destroy'])->name('data-jamaah.destroy');
Route::get('/data-jamaah/{id}', [JamaahController::class, 'show'])->name('data-jamaah.show');

Route::get('/paket-umroh', [PaketUmrohController::class, 'index'])->name('paket-umroh');
Route::get('/paket-umroh/create', [PaketUmrohController::class, 'create'])->name('paket-umroh.create');
Route::post('/paket-umroh', [PaketUmrohController::class, 'store'])->name('paket-umroh.store');
Route::get('/paket-umroh/export', [PaketUmrohController::class, 'export'])->name('paket-umroh.export');
Route::get('/paket-umroh/print', [PaketUmrohController::class, 'printData'])->name('paket-umroh.print');
Route::get('/paket-umroh/{id}/edit', [PaketUmrohController::class, 'edit'])->name('paket-umroh.edit');
Route::put('/paket-umroh/{id}', [PaketUmrohController::class, 'update'])->name('paket-umroh.update');
Route::delete('/paket-umroh/{id}', [PaketUmrohController::class, 'destroy'])->name('paket-umroh.destroy');
Route::get('/paket-umroh/{id}', [PaketUmrohController::class, 'show'])->name('paket-umroh.show');

Route::get('/paket-haji', [PaketHajiController::class, 'index'])->name('paket-haji');
Route::get('/paket-haji/create', [PaketHajiController::class, 'create'])->name('paket-haji.create');
Route::post('/paket-haji', [PaketHajiController::class, 'store'])->name('paket-haji.store');
Route::get('/paket-haji/export', [PaketHajiController::class, 'export'])->name('paket-haji.export');
Route::get('/paket-haji/print', [PaketHajiController::class, 'printData'])->name('paket-haji.print');
Route::get('/paket-haji/{id}/edit', [PaketHajiController::class, 'edit'])->name('paket-haji.edit');
Route::put('/paket-haji/{id}', [PaketHajiController::class, 'update'])->name('paket-haji.update');
Route::delete('/paket-haji/{id}', [PaketHajiController::class, 'destroy'])->name('paket-haji.destroy');
Route::get('/paket-haji/{id}', [PaketHajiController::class, 'show'])->name('paket-haji.show');

Route::get('/data-tiket', [TicketController::class, 'index'])->name('data-ticket');
Route::get('/data-tiket/create', [TicketController::class, 'create'])->name('data-ticket.create');
Route::post('/data-tiket', [TicketController::class, 'store'])->name('data-ticket.store');
Route::get('/data-tiket/{id}/edit', [TicketController::class, 'edit'])->name('data-ticket.edit');
Route::put('/data-tiket/{id}', [TicketController::class, 'update'])->name('data-ticket.update');
Route::delete('/data-tiket/{id}', [TicketController::class, 'destroy'])->name('data-ticket.destroy');
Route::get('/data-tiket/{id}', [TicketController::class, 'show'])->name('data-ticket.show');

Route::get('/tabungan-umroh', [TabunganUmrohController::class, 'index'])->name('tabungan-umroh');
Route::get('/tabungan-umroh/create', [TabunganUmrohController::class, 'create'])->name('tabungan-umroh.create');
Route::post('/tabungan-umroh', [TabunganUmrohController::class, 'store'])->name('tabungan-umroh.store');
Route::get('/tabungan-umroh/{id}/edit', [TabunganUmrohController::class, 'edit'])->name('tabungan-umroh.edit');
Route::put('/tabungan-umroh/{id}', [TabunganUmrohController::class, 'update'])->name('tabungan-umroh.update');
Route::delete('/tabungan-umroh/{id}', [TabunganUmrohController::class, 'destroy'])->name('tabungan-umroh.destroy');
Route::get('/tabungan-umroh/{id}', [TabunganUmrohController::class, 'show'])->name('tabungan-umroh.show');

Route::get('/tabungan-haji', [TabunganHajiController::class, 'index'])->name('tabungan-haji');
Route::get('/tabungan-haji/create', [TabunganHajiController::class, 'create'])->name('tabungan-haji.create');
Route::post('/tabungan-haji', [TabunganHajiController::class, 'store'])->name('tabungan-haji.store');
Route::get('/tabungan-haji/{id}/edit', [TabunganHajiController::class, 'edit'])->name('tabungan-haji.edit');
Route::put('/tabungan-haji/{id}', [TabunganHajiController::class, 'update'])->name('tabungan-haji.update');
Route::delete('/tabungan-haji/{id}', [TabunganHajiController::class, 'destroy'])->name('tabungan-haji.destroy');
Route::get('/tabungan-haji/{id}', [TabunganHajiController::class, 'show'])->name('tabungan-haji.show');

Route::get('/setoran-umroh', [SetoranUmrohController::class, 'generalIndex'])->name('setoran-umroh.general');
Route::get('/setoran-umroh/{id}', [SetoranUmrohController::class, 'index'])->name('setoran-umroh.index');
Route::get('/setoran-umroh/{id}/create', [SetoranUmrohController::class, 'create'])->name('setoran-umroh.create');
Route::post('/setoran-umroh/{id}', [SetoranUmrohController::class, 'store'])->name('setoran-umroh.store');
Route::get('/setoran-umroh/transaksi/{id}/edit', [SetoranUmrohController::class, 'edit'])->name('setoran-umroh.edit');
Route::put('/setoran-umroh/transaksi/{id}', [SetoranUmrohController::class, 'update'])->name('setoran-umroh.update');
Route::delete('/setoran-umroh/transaksi/{id}', [SetoranUmrohController::class, 'destroy'])->name('setoran-umroh.destroy');

Route::get('/setoran-haji', [SetoranHajiController::class, 'generalIndex'])->name('setoran-haji.general');
Route::get('/setoran-haji/{id}', [SetoranHajiController::class, 'index'])->name('setoran-haji.index');
Route::get('/setoran-haji/{id}/create', [SetoranHajiController::class, 'create'])->name('setoran-haji.create');
Route::post('/setoran-haji/{id}', [SetoranHajiController::class, 'store'])->name('setoran-haji.store');
Route::get('/setoran-haji/transaksi/{id}/edit', [SetoranHajiController::class, 'edit'])->name('setoran-haji.edit');
Route::put('/setoran-haji/transaksi/{id}', [SetoranHajiController::class, 'update'])->name('setoran-haji.update');
Route::delete('/setoran-haji/transaksi/{id}', [SetoranHajiController::class, 'destroy'])->name('setoran-haji.destroy');

Route::get('/stock-opname', [StockOpnameController::class, 'index'])->name('stock-opname.index');
Route::get('/stock-opname/create', [StockOpnameController::class, 'create'])->name('stock-opname.create');
Route::post('/stock-opname', [StockOpnameController::class, 'store'])->name('stock-opname.store');
Route::get('/stock-opname/{id}', [StockOpnameController::class, 'show'])->name('stock-opname.show');
Route::get('/stock-opname/{id}/edit', [StockOpnameController::class, 'edit'])->name('stock-opname.edit');
Route::put('/stock-opname/{id}', [StockOpnameController::class, 'update'])->name('stock-opname.update');
Route::delete('/stock-opname/{id}', [StockOpnameController::class, 'destroy'])->name('stock-opname.destroy');

Route::get('/pembelian-produk', [PembelianProdukController::class, 'index'])->name('pembelian-produk.index');
Route::get('/pembelian-produk/create', [PembelianProdukController::class, 'create'])->name('pembelian-produk.create');
Route::post('/pembelian-produk', [PembelianProdukController::class, 'store'])->name('pembelian-produk.store');
Route::get('/pembelian-produk/{id}', [PembelianProdukController::class, 'show'])->name('pembelian-produk.show');
Route::get('/pembelian-produk/{id}/edit', [PembelianProdukController::class, 'edit'])->name('pembelian-produk.edit');
Route::put('/pembelian-produk/{id}', [PembelianProdukController::class, 'update'])->name('pembelian-produk.update');
Route::delete('/pembelian-produk/{id}', [PembelianProdukController::class, 'destroy'])->name('pembelian-produk.destroy');

// Pengeluaran Produk Routes
Route::get('/pengeluaran-produk', [PengeluaranProdukController::class, 'index'])->name('pengeluaran-produk.index');
Route::get('/pengeluaran-produk/create', [PengeluaranProdukController::class, 'create'])->name('pengeluaran-produk.create');
Route::post('/pengeluaran-produk', [PengeluaranProdukController::class, 'store'])->name('pengeluaran-produk.store');
Route::get('/pengeluaran-produk/{id}', [PengeluaranProdukController::class, 'show'])->name('pengeluaran-produk.show');
Route::get('/pengeluaran-produk/{id}/edit', [PengeluaranProdukController::class, 'edit'])->name('pengeluaran-produk.edit');
Route::put('/pengeluaran-produk/{id}', [PengeluaranProdukController::class, 'update'])->name('pengeluaran-produk.update');
Route::delete('/pengeluaran-produk/{id}', [PengeluaranProdukController::class, 'destroy'])->name('pengeluaran-produk.destroy');

// Transaksi Layanan Routes


Route::get('/transaksi-layanan', [TransaksiLayananController::class, 'index'])->name('transaksi-layanan.index');
Route::get('/transaksi-layanan/create', [TransaksiLayananController::class, 'create'])->name('transaksi-layanan.create');
Route::post('/transaksi-layanan', [TransaksiLayananController::class, 'store'])->name('transaksi-layanan.store');
Route::get('/transaksi-layanan/{id}', [TransaksiLayananController::class, 'show'])->name('transaksi-layanan.show');
Route::get('/transaksi-layanan/{id}/edit', [TransaksiLayananController::class, 'edit'])->name('transaksi-layanan.edit');
Route::put('/transaksi-layanan/{id}', [TransaksiLayananController::class, 'update'])->name('transaksi-layanan.update');
Route::delete('/transaksi-layanan/{id}', [TransaksiLayananController::class, 'destroy'])->name('transaksi-layanan.destroy');

// Transaksi Tiket Routes


Route::get('/transaksi-tiket', [TransaksiTiketController::class, 'index'])->name('transaksi-tiket.index');
Route::get('/transaksi-tiket/create', [TransaksiTiketController::class, 'create'])->name('transaksi-tiket.create');
Route::post('/transaksi-tiket', [TransaksiTiketController::class, 'store'])->name('transaksi-tiket.store');
Route::get('/transaksi-tiket/{id}', [TransaksiTiketController::class, 'show'])->name('transaksi-tiket.show');
Route::get('/transaksi-tiket/{id}/edit', [TransaksiTiketController::class, 'edit'])->name('transaksi-tiket.edit');
Route::put('/transaksi-tiket/{id}', [TransaksiTiketController::class, 'update'])->name('transaksi-tiket.update');
Route::delete('/transaksi-tiket/{id}', [TransaksiTiketController::class, 'destroy'])->name('transaksi-tiket.destroy');

// Keberangkatan Umroh Routes


Route::get('/keberangkatan-umroh', [KeberangkatanUmrohController::class, 'index'])->name('keberangkatan-umroh.index');
Route::get('/keberangkatan-umroh/create', [KeberangkatanUmrohController::class, 'create'])->name('keberangkatan-umroh.create');
Route::post('/keberangkatan-umroh', [KeberangkatanUmrohController::class, 'store'])->name('keberangkatan-umroh.store');
Route::get('/keberangkatan-umroh/{id}', [KeberangkatanUmrohController::class, 'show'])->name('keberangkatan-umroh.show');
Route::get('/keberangkatan-umroh/{id}/edit', [KeberangkatanUmrohController::class, 'edit'])->name('keberangkatan-umroh.edit');
Route::put('/keberangkatan-umroh/{id}', [KeberangkatanUmrohController::class, 'update'])->name('keberangkatan-umroh.update');
Route::delete('/keberangkatan-umroh/{id}', [KeberangkatanUmrohController::class, 'destroy'])->name('keberangkatan-umroh.destroy');

// Customer Umroh (Manifest) Routes
Route::get('/customer-umroh/{id}', [CustomerUmrohController::class, 'index'])->name('customer-umroh.index');
Route::get('/customer-umroh/{id}/create', [CustomerUmrohController::class, 'create'])->name('customer-umroh.create');
Route::post('/customer-umroh/{id}', [CustomerUmrohController::class, 'store'])->name('customer-umroh.store');
Route::get('/customer-umroh/detail/{id}', [CustomerUmrohController::class, 'show'])->name('customer-umroh.show');
Route::get('/customer-umroh/edit/{id}', [CustomerUmrohController::class, 'edit'])->name('customer-umroh.edit');
Route::put('/customer-umroh/update/{id}', [CustomerUmrohController::class, 'update'])->name('customer-umroh.update');
Route::delete('/customer-umroh/delete/{id}', [CustomerUmrohController::class, 'destroy'])->name('customer-umroh.destroy');

// Keberangkatan Haji Routes


Route::get('/keberangkatan-haji', [KeberangkatanHajiController::class, 'index'])->name('keberangkatan-haji.index');
Route::get('/keberangkatan-haji/create', [KeberangkatanHajiController::class, 'create'])->name('keberangkatan-haji.create');
Route::post('/keberangkatan-haji', [KeberangkatanHajiController::class, 'store'])->name('keberangkatan-haji.store');
Route::get('/keberangkatan-haji/{id}', [KeberangkatanHajiController::class, 'show'])->name('keberangkatan-haji.show');
Route::get('/keberangkatan-haji/{id}/edit', [KeberangkatanHajiController::class, 'edit'])->name('keberangkatan-haji.edit');
Route::put('/keberangkatan-haji/{id}', [KeberangkatanHajiController::class, 'update'])->name('keberangkatan-haji.update');
Route::delete('/keberangkatan-haji/{id}', [KeberangkatanHajiController::class, 'destroy'])->name('keberangkatan-haji.destroy');

// Customer Haji (Manifest) Routes

Route::get('/customer-haji/{id}', [CustomerHajiController::class, 'index'])->name('customer-haji.index');
Route::get('/customer-haji/{id}/create', [CustomerHajiController::class, 'create'])->name('customer-haji.create');
Route::post('/customer-haji/{id}', [CustomerHajiController::class, 'store'])->name('customer-haji.store');

// Unified Pendaftaran Umroh Routes

Route::get('/pendaftaran-umroh', [PendaftaranUmrohController::class, 'index'])->name('pendaftaran-umroh.index');
Route::get('/pendaftaran-umroh/create', [PendaftaranUmrohController::class, 'create'])->name('pendaftaran-umroh.create');
Route::post('/pendaftaran-umroh', [PendaftaranUmrohController::class, 'store'])->name('pendaftaran-umroh.store');
Route::get('/pendaftaran-umroh/export', [PendaftaranUmrohController::class, 'export'])->name('pendaftaran-umroh.export');
Route::get('/pendaftaran-umroh/print', [PendaftaranUmrohController::class, 'printData'])->name('pendaftaran-umroh.print');
Route::get('/pendaftaran-umroh/{id}', [PendaftaranUmrohController::class, 'show'])->name('pendaftaran-umroh.show');
Route::get('/pendaftaran-umroh/{id}/edit', [PendaftaranUmrohController::class, 'edit'])->name('pendaftaran-umroh.edit');
Route::put('/pendaftaran-umroh/{id}', [PendaftaranUmrohController::class, 'update'])->name('pendaftaran-umroh.update');
Route::delete('/pendaftaran-umroh/{id}', [PendaftaranUmrohController::class, 'destroy'])->name('pendaftaran-umroh.destroy');

Route::get('/pendaftaran-haji', [PendaftaranHajiController::class, 'index'])->name('pendaftaran-haji.index');
Route::get('/pendaftaran-haji/create', [PendaftaranHajiController::class, 'create'])->name('pendaftaran-haji.create');
Route::post('/pendaftaran-haji', [PendaftaranHajiController::class, 'store'])->name('pendaftaran-haji.store');
Route::get('/pendaftaran-haji/export', [PendaftaranHajiController::class, 'export'])->name('pendaftaran-haji.export');
Route::get('/pendaftaran-haji/print', [PendaftaranHajiController::class, 'printData'])->name('pendaftaran-haji.print');
Route::get('/pendaftaran-haji/{id}', [PendaftaranHajiController::class, 'show'])->name('pendaftaran-haji.show');
Route::get('/pendaftaran-haji/{id}/edit', [PendaftaranHajiController::class, 'edit'])->name('pendaftaran-haji.edit');
Route::put('/pendaftaran-haji/{id}', [PendaftaranHajiController::class, 'update'])->name('pendaftaran-haji.update');
Route::delete('/pendaftaran-haji/{id}', [PendaftaranHajiController::class, 'destroy'])->name('pendaftaran-haji.destroy');

// Unified Pendaftaran Haji Routes (Cleaned up)

Route::get('/setoran-haji/{id}', [SetoranHajiController::class, 'index'])->name('setoran-haji.index');
Route::get('/setoran-haji/{id}/create', [SetoranHajiController::class, 'create'])->name('setoran-haji.create');
Route::post('/setoran-haji/{id}', [SetoranHajiController::class, 'store'])->name('setoran-haji.store');
Route::get('/setoran-haji/transaksi/{id}/edit', [SetoranHajiController::class, 'edit'])->name('setoran-haji.edit');
Route::put('/setoran-haji/transaksi/{id}', [SetoranHajiController::class, 'update'])->name('setoran-haji.update');
Route::delete('/setoran-haji/transaksi/{id}', [SetoranHajiController::class, 'destroy'])->name('setoran-haji.destroy');

// Bonus Agent Routes

Route::get('/bonus-agent/export', [BonusAgentController::class, 'export'])->name('bonus-agent.export');
Route::get('/bonus-agent/print', [BonusAgentController::class, 'printData'])->name('bonus-agent.print');
Route::get('/bonus-agent', [BonusAgentController::class, 'index'])->name('bonus-agent.index');
Route::post('/bonus-agent', [BonusAgentController::class, 'store'])->name('bonus-agent.store');
Route::get('/bonus-agent/{id}/export', [BonusAgentController::class, 'exportDetail'])->name('bonus-agent.export-detail');
Route::get('/bonus-agent/{id}/print', [BonusAgentController::class, 'printDetail'])->name('bonus-agent.print-detail');
Route::get('/bonus-agent/{id}', [BonusAgentController::class, 'show'])->name('bonus-agent.show');
Route::get('/bonus-agent/{id}/jamaah-umroh', [BonusAgentController::class, 'showJamaahUmroh'])->name('bonus-agent.jamaah-umroh');
Route::get('/bonus-agent/{id}/jamaah-haji', [BonusAgentController::class, 'showJamaahHaji'])->name('bonus-agent.jamaah-haji');
Route::get('/bonus-agent/payment/{id}/edit', [BonusAgentController::class, 'edit'])->name('bonus-agent.edit');
Route::put('/bonus-agent/payment/{id}', [BonusAgentController::class, 'update'])->name('bonus-agent.update');
Route::get('/payment-agent/{id}', [BonusAgentController::class, 'showPaymentHistory'])->name('payment-agent.show');

// Pembayaran Umroh Routes

Route::get('/pembayaran-umroh', [PembayaranUmrohController::class, 'index'])->name('pembayaran-umroh.index');
Route::get('/pembayaran-umroh/detail/{id}', [PembayaranUmrohController::class, 'show'])->name('pembayaran-umroh.show'); // Renamed URL, keep name for now or update? Better update name to .detail to be clear, but user might have used .show. Let's keep .show name OR update references. Plan said rename route. Let's rename URL + Name and update controller return view if needed? No, controller returns view.
// Wait, I should keep .show name if I don't want to break existing links? But I need to change the URL.
// Actually, user wants `pembayaran-umroh/{id}` to be HISTORY.
// So:
Route::get('/pembayaran-umroh/{id}', [PembayaranUmrohController::class, 'history'])->name('pembayaran-umroh.history'); // The ID here is CustomerUmroh ID
Route::get('/pembayaran-umroh/detail/{id}', [PembayaranUmrohController::class, 'show'])->name('pembayaran-umroh.detail'); // The ID here is PembayaranUmroh ID
Route::get('/pembayaran-umroh/{id}/edit', [PembayaranUmrohController::class, 'edit'])->name('pembayaran-umroh.edit');
Route::put('/pembayaran-umroh/{id}', [PembayaranUmrohController::class, 'update'])->name('pembayaran-umroh.update');
Route::get('/pembayaran-umroh/create/{id}', [PembayaranUmrohController::class, 'createPayment'])->name('pembayaran-umroh.create-payment');
Route::post('/pembayaran-umroh/store/{id}', [PembayaranUmrohController::class, 'storePayment'])->name('pembayaran-umroh.store-payment');


Route::get('/pembayaran-haji', [PembayaranHajiController::class, 'index'])->name('pembayaran-haji.index');
Route::get('/pembayaran-haji/{id}', [PembayaranHajiController::class, 'history'])->name('pembayaran-haji.history');
Route::get('/pembayaran-haji/detail/{id}', [PembayaranHajiController::class, 'show'])->name('pembayaran-haji.detail');
Route::get('/pembayaran-haji/{id}/edit', [PembayaranHajiController::class, 'edit'])->name('pembayaran-haji.edit');
Route::put('/pembayaran-haji/{id}', [PembayaranHajiController::class, 'update'])->name('pembayaran-haji.update');
Route::get('/pembayaran-haji/create/{id}', [PembayaranHajiController::class, 'createPayment'])->name('pembayaran-haji.create-payment');
Route::post('/pembayaran-haji/store/{id}', [PembayaranHajiController::class, 'storePayment'])->name('pembayaran-haji.store-payment');


Route::resource('pengeluaran-umroh', PengeluaranUmrohController::class);


Route::resource('pengeluaran-haji', PengeluaranHajiController::class);


Route::resource('pengeluaran-umum', PengeluaranUmumController::class);


Route::resource('pemasukan-umum', PemasukanUmumController::class);


Route::get('surat-rekomendasi/export', [SuratRekomendasiController::class, 'export'])->name('surat-rekomendasi.export');
Route::get('surat-rekomendasi/print', [SuratRekomendasiController::class, 'printData'])->name('surat-rekomendasi.print');
Route::get('surat-rekomendasi/{id}/export-pdf', [SuratRekomendasiController::class, 'exportPdf'])->name('surat-rekomendasi.export-pdf');
Route::resource('surat-rekomendasi', SuratRekomendasiController::class);


Route::get('surat-izin-cuti/{id}/export-pdf', [SuratIzinCutiController::class, 'exportPdf'])->name('surat-izin-cuti.export-pdf');
Route::resource('surat-izin-cuti', SuratIzinCutiController::class);


Route::get('laporan-umroh', [LaporanUmrohController::class, 'index'])->name('laporan-umroh.index');
Route::get('laporan-umroh/{id}', [LaporanUmrohController::class, 'show'])->name('laporan-umroh.show');


Route::get('laporan-haji', [LaporanHajiController::class, 'index'])->name('laporan-haji.index');
Route::get('laporan-haji/{id}', [LaporanHajiController::class, 'show'])->name('laporan-haji.show');


Route::get('pembayaran-tiket', [PembayaranTiketController::class, 'index'])->name('pembayaran-tiket.index');
Route::get('pembayaran-tiket/{id}', [PembayaranTiketController::class, 'show'])->name('pembayaran-tiket.show');
Route::get('pembayaran-tiket/{id}/create-payment', [PembayaranTiketController::class, 'createPayment'])->name('pembayaran-tiket.create-payment');
Route::post('pembayaran-tiket/{id}/store-payment', [PembayaranTiketController::class, 'storePayment'])->name('pembayaran-tiket.store-payment');

Route::resource('data-kota', \App\Http\Controllers\KotaController::class);

// Individual Payment Actions
Route::get('pembayaran-tiket/detail/{id}', [PembayaranTiketController::class, 'detail'])->name('pembayaran-tiket.detail');
Route::get('pembayaran-tiket/edit/{id}', [PembayaranTiketController::class, 'edit'])->name('pembayaran-tiket.edit');
Route::put('pembayaran-tiket/update/{id}', [PembayaranTiketController::class, 'update'])->name('pembayaran-tiket.update');
Route::delete('pembayaran-tiket/delete/{id}', [PembayaranTiketController::class, 'destroy'])->name('pembayaran-tiket.destroy');
Route::get('pembayaran-tiket/pdf/{id}', [PembayaranTiketController::class, 'exportPdf'])->name('pembayaran-tiket.export-pdf');
Route::get('pembayaran-tiket/print/{id}', [PembayaranTiketController::class, 'printPdf'])->name('pembayaran-tiket.print-pdf');
Route::get('pembayaran-tiket-export', [PembayaranTiketController::class, 'export'])->name('pembayaran-tiket.export');
Route::get('pembayaran-tiket-print', [PembayaranTiketController::class, 'printData'])->name('pembayaran-tiket.print-data');


Route::get('/pembayaran-layanan', [PembayaranLayananController::class, 'index'])->name('pembayaran-layanan.index');
Route::get('/pembayaran-layanan/{id}', [PembayaranLayananController::class, 'show'])->name('pembayaran-layanan.show');
Route::get('/pembayaran-layanan/{id}/create-payment', [PembayaranLayananController::class, 'createPayment'])->name('pembayaran-layanan.create-payment');
Route::post('/pembayaran-layanan/{id}/store-payment', [PembayaranLayananController::class, 'storePayment'])->name('pembayaran-layanan.store-payment');
    Route::get('/pembayaran-layanan/{id}/edit', [PembayaranLayananController::class, 'edit'])->name('pembayaran-layanan.edit');
    Route::put('/pembayaran-layanan/{id}', [PembayaranLayananController::class, 'update'])->name('pembayaran-layanan.update');
    Route::delete('/pembayaran-layanan/{id}', [PembayaranLayananController::class, 'destroy'])->name('pembayaran-layanan.destroy');




// dashboard pages

Route::get('laporan-keuangan', [LaporanKeuanganController::class, 'index'])->name('laporan-keuangan.index');
Route::get('laporan-keuangan/export', [LaporanKeuanganController::class, 'export'])->name('laporan-keuangan.export');


Route::get('rugi-laba-penjualan', [RugiLabaPenjualanController::class, 'index'])->name('rugi-laba-penjualan.index');

Route::redirect('/', '/dashboard');

// calender pages
Route::get('/calendar', function () {
    return view('pages.calender', ['title' => 'Calendar']);
})->name('calendar');



// form pages
Route::get('/form-elements', function () {
    return view('pages.form.form-elements', ['title' => 'Form Elements']);
})->name('form-elements');

// tables pages
Route::get('/basic-tables', function () {
    return view('pages.tables.basic-tables', ['title' => 'Basic Tables']);
})->name('basic-tables');

// pages

Route::get('/blank', function () {
    return view('pages.blank', ['title' => 'Blank']);
})->name('blank');

// error pages
Route::get('/error-404', function () {
    return view('pages.errors.error-404', ['title' => 'Error 404']);
})->name('error-404');

// chart pages
Route::get('/line-chart', function () {
    return view('pages.chart.line-chart', ['title' => 'Line Chart']);
})->name('line-chart');

Route::get('/bar-chart', function () {
    return view('pages.chart.bar-chart', ['title' => 'Bar Chart']);
})->name('bar-chart');


// authentication pages



    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

// ui elements pages
Route::get('/alerts', function () {
    return view('pages.ui-elements.alerts', ['title' => 'Alerts']);
})->name('alerts');

Route::get('/avatars', function () {
    return view('pages.ui-elements.avatars', ['title' => 'Avatars']);
})->name('avatars');

Route::get('/badge', function () {
    return view('pages.ui-elements.badges', ['title' => 'Badges']);
})->name('badges');

Route::get('/buttons', function () {
    return view('pages.ui-elements.buttons', ['title' => 'Buttons']);
})->name('buttons');

Route::get('/image', function () {
    return view('pages.ui-elements.images', ['title' => 'Images']);
})->name('images');

Route::get('/videos', function () {
    return view('pages.ui-elements.videos', ['title' => 'Videos']);
})->name('videos');






















});
