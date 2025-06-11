// lib/screens/rewards/merchandise_screen.dart
import 'package:flutter/material.dart';
import 'package:mobile/data/models/merchandise/merchandise.dart';
import 'package:mobile/data/models/merchandise/penukaran_merchandise.dart';
import 'package:mobile/data/datasource/local/auth_local_datasource.dart';
import 'package:mobile/data/models/pembeli/ModelPembeli.dart'; // Menggunakan model Pembeli yang konsisten
import 'package:mobile/data/service/reward_service.dart';
import 'package:intl/intl.dart';

class MerchandiseScreen extends StatefulWidget {
  const MerchandiseScreen({super.key});

  @override
  State<MerchandiseScreen> createState() => _MerchandiseScreenState();
}

class _MerchandiseScreenState extends State<MerchandiseScreen> {
  late Future<List<Merchandise>> _merchandiseCatalogFuture;
  late Future<PembeliModel> _pembeliProfileFuture; // Menggunakan model Pembeli
  final RewardService _rewardService = RewardService();

  bool _isRefreshing = false; // Flag untuk menampilkan loading state
  int _currentPembeliPoints = 0;
  final Map<int, int> _itemsToClaim = {}; // {merchandiseId: quantity}

  List<Merchandise> _cachedMerchandiseCatalog =
      []; // Cache untuk akses item berdasarkan ID

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    setState(() {
      _merchandiseCatalogFuture = _rewardService.fetchMerchandiseCatalog();
      _pembeliProfileFuture = _rewardService.fetchCurrentPembeliProfile();
    });

    // Handle catalog data to cache it
    _merchandiseCatalogFuture.then((catalog) {
      setState(() {
        _cachedMerchandiseCatalog = catalog;
      });
    }).catchError((error) {
      print('Error caching merchandise catalog: $error');
      // Tidak perlu SnackBar di sini, error sudah ditangani oleh FutureBuilder
    });

    _pembeliProfileFuture.then((pembeliData) {
      setState(() {
        _currentPembeliPoints =
            pembeliData.point ?? 0; // Akses properti 'point'
      });
    }).catchError((error) {
      print('Error fetching pembeli data: $error');
      if (mounted) {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal memuat poin Anda: $error')),
        );
      }
      setState(() {
        _currentPembeliPoints = 0;
      });
    });
    setState(() {
      _isRefreshing = false;
    });
  }

// Fungsi untuk toggle (memilih/membatalkan pilihan) item untuk klaim multi-item
  void _toggleItemClaim(Merchandise merchandise) {
    setState(() {
      if (_itemsToClaim.containsKey(merchandise.id)) {
        _itemsToClaim.remove(merchandise.id);
      } else {
        _itemsToClaim[merchandise.id] =
            1; // Default kuantitas 1 saat pertama kali dipilih
      }
    });
  }

// Fungsi untuk menambah kuantitas item yang dipilih
  void _increaseQuantity(int merchId) {
    setState(() {
      final currentQty = _itemsToClaim[merchId] ?? 0;
      final merchandise =
          _cachedMerchandiseCatalog.firstWhere((m) => m.id == merchId);
      if (currentQty < merchandise.jumlah) {
        // Cek agar tidak melebihi stok
        _itemsToClaim.update(merchId, (value) => value + 1);
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(
              content: Text(
                  'Tidak bisa menambahkan lebih banyak, stok tidak cukup.')),
        );
      }
    });
  }

// Fungsi untuk mengurangi kuantitas item yang dipilih
  void _decreaseQuantity(int merchId) {
    setState(() {
      if ((_itemsToClaim[merchId] ?? 0) > 1) {
        _itemsToClaim.update(merchId, (value) => value - 1);
      } else {
        _itemsToClaim.remove(merchId); // Hapus jika kuantitas jadi 0
      }
    });
  }

// Fungsi untuk klaim merchandise tunggal (dipanggil dari tombol 'Klaim' di card)
  Future<void> _claimSingleMerchandise(Merchandise merchandise) async {
    if (_currentPembeliPoints < merchandise.nilaiPoint) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
            content: Text('Poin Anda tidak cukup untuk klaim item ini!')),
      );
      return;
    }
    if (merchandise.jumlah <= 0) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text('Stok item ini habis.')),
      );
      return;
    }
    bool? confirm = await showDialog<bool>(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text('Konfirmasi Klaim Merchandise'),
          content: RichText(
            text: TextSpan(
              style: Theme.of(context).textTheme.bodyMedium,
              children: [
                const TextSpan(text: 'Anda akan menukarkan '),
                TextSpan(
                  text: '${merchandise.namaMerchandise}',
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
                const TextSpan(text: ' dengan '),
                TextSpan(
                  text: '${merchandise.nilaiPoint} Poin',
                  style: const TextStyle(
                      color: Colors.deepOrange, fontWeight: FontWeight.bold),
                ),
                TextSpan(
                    text:
                        '.\n\nPoin Anda saat ini: ${NumberFormat('#,###').format(_currentPembeliPoints)}'),
                const TextSpan(
                    text: '\nApakah Anda yakin ingin melanjutkan klaim ini?'),
              ],
            ),
          ),
          actions: [
            TextButton(
              onPressed: () => Navigator.of(context).pop(false),
              child: const Text('Batal'),
            ),
            ElevatedButton(
              onPressed: () => Navigator.of(context).pop(true),
              child: const Text('Klaim'),
            ),
          ],
        );
      },
    );

    // if (confirm == true) {
    //   try {
    //     final currentPembeliProfile = await _pembeliProfileFuture;
    //     await _rewardService.claimMerchandise([
    //       {
    //         'id_merchandise': merchandise.id,
    //         'jumlah': 1,
    //         'id_pembeli': currentPembeliProfile.id
    //       }
    //     ]);
    //     ScaffoldMessenger.of(context).showSnackBar(
    //       const SnackBar(content: Text('Klaim merchandise berhasil!')),
    //     );
    //     _loadData();
    //   } catch (e) {
    //     print('Error claiming single merchandise: $e');
    //     ScaffoldMessenger.of(context).showSnackBar(
    //       SnackBar(content: Text('Gagal klaim merchandise: ${e.toString()}')),
    //     );
    //   }
    // }

    if (confirm == true) {
      try {
        final currentPembeliProfile = await _pembeliProfileFuture;
        await _rewardService.claimMerchandise([
          {
            'id_merchandise': merchandise.id,
            'jumlah': 1,
            'id_pembeli': currentPembeliProfile.id
          }
        ]);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Klaim merchandise berhasil!')),
        );
        _loadData();
      } catch (e) {
        if (e.toString().contains('Semua item berhasil ditukarkan')) {
          // Jangan tampilkan error jika ini pesan dari backend
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Klaim merchandise berhasil!')),
          );
        } else {
          print('Error claiming single merchandise: $e');
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Gagal klaim merchandise: ${e.toString()}')),
          );
        }
      }
    }
  }

  // Fungsi untuk klaim semua item yang dipilih di keranjang
  Future<void> _claimAllSelectedMerchandise() async {
    if (_itemsToClaim.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
            content:
                Text('Pilih merchandise yang ingin diklaim terlebih dahulu.')),
      );
      return;
    }

    int totalPointsNeeded = 0;
    List<Map<String, dynamic>> itemsPayload = [];

    _itemsToClaim.forEach((merchId, quantity) {
      final merch =
          _cachedMerchandiseCatalog.firstWhere((m) => m.id == merchId);
      totalPointsNeeded += merch.nilaiPoint * quantity;
      itemsPayload.add({'id_merchandise': merchId, 'jumlah': quantity});
    });

    if (_currentPembeliPoints < totalPointsNeeded) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
            content: Text('Poin Anda tidak cukup untuk klaim semua item ini!')),
      );
      return;
    }

    // Bangun daftar detail klaim untuk dialog konfirmasi
    List<Widget> confirmationDetails = [];
    _itemsToClaim.forEach((merchId, quantity) {
      final merch =
          _cachedMerchandiseCatalog.firstWhere((m) => m.id == merchId);
      confirmationDetails.add(Text(
          '- ${merch.namaMerchandise} x $quantity (${merch.nilaiPoint * quantity} Poin)'));
    });

    bool? confirm = await showDialog<bool>(
      context: context,
      builder: (context) {
        return AlertDialog(
          title: const Text('Konfirmasi Klaim Merchandise'),
          content: SizedBox(
            width: double.maxFinite,
            child: SingleChildScrollView(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                      'Poin Anda saat ini: ${NumberFormat('#,###').format(_currentPembeliPoints)}'),
                  Text(
                      'Poin yang dibutuhkan: ${NumberFormat('#,###').format(totalPointsNeeded)}'),
                  const SizedBox(height: 10),
                  const Text('Detail Klaim:',
                      style: TextStyle(fontWeight: FontWeight.bold)),
                  const SizedBox(height: 6),
                  ...confirmationDetails, // Detail item yang diklaim
                  const SizedBox(height: 12),
                  const Text('Apakah Anda yakin ingin melanjutkan klaim ini?'),
                ],
              ),
            ),
          ),
          actions: [
            TextButton(
                onPressed: () => Navigator.of(context).pop(false),
                child: const Text('Batal')),
            ElevatedButton(
                onPressed: () => Navigator.of(context).pop(true),
                child: const Text('Klaim Semua')),
          ],
        );
      },
    );

    // if (confirm == true) {
    //   try {
    //     final currentPembeliProfile = await _pembeliProfileFuture;
    //     final finalItemsPayload = itemsPayload
    //         .map((item) => {
    //               ...item,
    //               'id_pembeli': currentPembeliProfile
    //                   .id, // Tambahkan id_pembeli ke setiap item
    //             })
    //         .toList();

    //     await _rewardService.claimMerchandise(finalItemsPayload);
    //     ScaffoldMessenger.of(context).showSnackBar(
    //       const SnackBar(content: Text('Klaim merchandise berhasil!')),
    //     );
    //     setState(() {
    //       _itemsToClaim.clear();
    //     });
    //     _loadData();
    //   } catch (e) {
    //     print('Error claiming all merchandise: $e');
    //     ScaffoldMessenger.of(context).showSnackBar(
    //       SnackBar(content: Text('Gagal klaim merchandise: ${e.toString()}')),
    //     );
    //   }
    // }

    if (confirm == true) {
      try {
        final currentPembeliProfile = await _pembeliProfileFuture;
        final finalItemsPayload = itemsPayload
            .map((item) => {
                  ...item,
                  'id_pembeli': currentPembeliProfile.id,
                })
            .toList();

        await _rewardService.claimMerchandise(finalItemsPayload);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Klaim merchandise berhasil!')),
        );
        setState(() {
          _itemsToClaim.clear();
        });
        _loadData();
      } catch (e) {
        if (e.toString().contains('Semua item berhasil ditukarkan')) {
          ScaffoldMessenger.of(context).showSnackBar(
            const SnackBar(content: Text('Klaim merchandise berhasil!')),
          );
        } else {
          print('Error claiming all merchandise: $e');
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text('Gagal klaim merchandise: ${e.toString()}')),
          );
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Tukar Poin Reward'),
        backgroundColor: Colors.green,
        foregroundColor: Colors.white,
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: _loadData,
          ),
        ],
      ),
      body: Column(
        children: [
          Padding(
            padding: const EdgeInsets.all(16),
            child: Card(
              elevation: 4,
              shape: RoundedRectangleBorder(
                  borderRadius: BorderRadius.circular(10)),
              child: Padding(
                padding: const EdgeInsets.all(16),
                child: Row(
                  children: [
                    const Icon(Icons.stars, color: Colors.amber, size: 30),
                    const SizedBox(width: 12),
                    Column(
                      crossAxisAlignment: CrossAxisAlignment.start,
                      children: [
                        const Text(
                          'Poin Anda Saat Ini:',
                          style: TextStyle(fontSize: 14, color: Colors.grey),
                        ),
                        Text(
                          NumberFormat('#,###').format(_currentPembeliPoints) +
                              ' Poin',
                          style: const TextStyle(
                            fontSize: 28,
                            fontWeight: FontWeight.bold,
                            color: Colors.green,
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ),
          ),
          const Padding(
            padding: EdgeInsets.symmetric(horizontal: 16, vertical: 8),
            child: Align(
              alignment: Alignment.centerLeft,
              child: Text(
                'Katalog Merchandise',
                style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
              ),
            ),
          ),
          Expanded(
            child: FutureBuilder<List<Merchandise>>(
              future: _merchandiseCatalogFuture,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }
                if (snapshot.hasError) {
                  return Center(child: Text('Error: ${snapshot.error}'));
                }
                if (!snapshot.hasData || snapshot.data!.isEmpty) {
                  return const Center(
                      child: Text('Tidak ada merchandise ditemukan.'));
                }
                final merchandiseList = snapshot.data!;

                return GridView.builder(
                  padding:
                      const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                  gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
                    crossAxisCount: 2,
                    mainAxisSpacing: 12,
                    crossAxisSpacing: 12,
                    childAspectRatio: 0.72,
                  ),
                  itemCount: merchandiseList.length,
                  itemBuilder: (context, index) {
                    final merch = merchandiseList[index];
                    return MerchandiseCard(
                      merchandise: merch,
                      currentPembeliPoints: _currentPembeliPoints,
                      isSelected: _itemsToClaim.containsKey(merch.id),
                      quantity: _itemsToClaim[merch.id] ?? 0,
                      onToggleSelect: _toggleItemClaim,
                      onClaimSingle: _claimSingleMerchandise,
                      onIncreaseQuantity: _increaseQuantity,
                      onDecreaseQuantity: _decreaseQuantity,
                    );
                  },
                );
              },
            ),
          ),
          if (_itemsToClaim.isNotEmpty)
            Padding(
              padding: const EdgeInsets.all(16),
              child: ElevatedButton.icon(
                icon: const Icon(Icons.card_giftcard),
                onPressed: _claimAllSelectedMerchandise,
                label: Text(
                  'Klaim ${_itemsToClaim.length} Item Terpilih (Total Poin: ${NumberFormat('#,###').format(_itemsToClaim.entries.fold<int>(
                    0,
                    (sum, entry) {
                      final merch = _cachedMerchandiseCatalog
                          .firstWhere((m) => m.id == entry.key);
                      return sum + (merch.nilaiPoint * entry.value);
                    },
                  ))})',
                ),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.green[700],
                  foregroundColor: Colors.white,
                  minimumSize: const Size.fromHeight(50),
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(10)),
                ),
              ),
            ),
        ],
      ),
    );
  }
}

class MerchandiseCard extends StatelessWidget {
  final Merchandise merchandise;
  final int currentPembeliPoints;
  final bool isSelected;
  final int quantity;
  final Function(Merchandise) onToggleSelect;
  final Function(Merchandise) onClaimSingle;
  final Function(int merchId) onIncreaseQuantity;
  final Function(int merchId) onDecreaseQuantity;

  const MerchandiseCard({
    super.key,
    required this.merchandise,
    required this.currentPembeliPoints,
    required this.isSelected,
    required this.quantity,
    required this.onToggleSelect,
    required this.onClaimSingle,
    required this.onIncreaseQuantity,
    required this.onDecreaseQuantity,
  });

  @override
  Widget build(BuildContext context) {
    final canClaimSingle = merchandise.jumlah > 0 &&
        currentPembeliPoints >= merchandise.nilaiPoint;

    return GestureDetector(
      onTap: () => onToggleSelect(merchandise),
      child: AnimatedContainer(
        duration: const Duration(milliseconds: 300),
        decoration: BoxDecoration(
          border: Border.all(
              color: isSelected ? Colors.green : Colors.grey.shade300,
              width: 2),
          borderRadius: BorderRadius.circular(12),
          color: isSelected ? Colors.green.shade50 : Colors.white,
          boxShadow: [
            BoxShadow(
              color: Colors.grey.shade200,
              blurRadius: 6,
              offset: const Offset(0, 2),
            ),
          ],
        ),
        padding: const EdgeInsets.all(12),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.stretch,
          children: [
            Expanded(
              flex: 4,
              child: Stack(
                children: [
                  AspectRatio(
                    aspectRatio: 1,
                    child: Container(
                      color: Colors.grey[100],
                      child: FadeInImage.assetNetwork(
                        placeholder: 'assets/placeholder.png',
                        image: merchandise.fullGambarUrl,
                        fit: BoxFit.contain,
                        imageErrorBuilder: (context, error, stackTrace) =>
                            Image.asset('assets/image_error.png',
                                fit: BoxFit.contain),
                      ),
                    ),
                  ),
                  Positioned(
                    top: 8,
                    right: 8,
                    child: Container(
                      padding: const EdgeInsets.symmetric(
                          horizontal: 6, vertical: 3),
                      decoration: BoxDecoration(
                        color:
                            merchandise.jumlah > 0 ? Colors.green : Colors.red,
                        borderRadius: BorderRadius.circular(6),
                      ),
                      child: Text(
                        merchandise.jumlah > 0
                            ? 'Stok: ${merchandise.jumlah}'
                            : 'Stok Habis',
                        style:
                            const TextStyle(color: Colors.white, fontSize: 11),
                      ),
                    ),
                  ),
                  if (isSelected)
                    const Positioned(
                      top: 8,
                      left: 8,
                      child: Icon(Icons.check_circle,
                          color: Colors.green, size: 26),
                    ),
                ],
              ),
            ),
            const SizedBox(height: 8),
            Text(
              merchandise.namaMerchandise,
              style: const TextStyle(
                fontWeight: FontWeight.bold,
                fontSize: 14,
                height: 1.3,
              ),
              maxLines: 2,
              overflow: TextOverflow.ellipsis,
            ),
            const SizedBox(height: 4),
            Text(
              '${NumberFormat('#,###').format(merchandise.nilaiPoint)} Poin',
              style: TextStyle(
                fontWeight: FontWeight.w600,
                color: canClaimSingle ? Colors.green[700] : Colors.grey[600],
                fontSize: 13,
              ),
            ),
            const SizedBox(height: 8),
            if (isSelected)
              Row(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  IconButton(
                    onPressed: quantity > 1
                        ? () => onDecreaseQuantity(merchandise.id)
                        : null,
                    icon: const Icon(Icons.remove_circle_outline,
                        color: Colors.green),
                    iconSize: 22,
                    tooltip: 'Kurangi jumlah',
                  ),
                  Text(
                    quantity.toString(),
                    style: const TextStyle(
                        fontSize: 16,
                        fontWeight: FontWeight.bold,
                        color: Colors.green),
                  ),
                  IconButton(
                    onPressed: merchandise.jumlah > quantity
                        ? () => onIncreaseQuantity(merchandise.id)
                        : null,
                    icon: const Icon(Icons.add_circle_outline,
                        color: Colors.green),
                    iconSize: 22,
                    tooltip: 'Tambah jumlah',
                  ),
                ],
              )
            else
              ElevatedButton(
                onPressed: canClaimSingle && merchandise.jumlah > 0
                    ? () => onClaimSingle(merchandise)
                    : null,
                style: ElevatedButton.styleFrom(
                  backgroundColor: canClaimSingle && merchandise.jumlah > 0
                      ? Colors.green
                      : Colors.grey,
                  foregroundColor: Colors.white,
                  minimumSize: const Size.fromHeight(36),
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(6)),
                ),
                child: Text(
                  merchandise.jumlah > 0
                      ? (canClaimSingle ? 'Klaim' : 'Poin Kurang')
                      : 'Stok Habis',
                  style: const TextStyle(fontSize: 12),
                ),
              ),
          ],
        ),
      ),
    );
  }
}

class MyClaimsHistoryScreen extends StatelessWidget {
  const MyClaimsHistoryScreen({super.key});

  @override
  Widget build(BuildContext context) {
    // Contoh placeholder untuk halaman riwayat klaim
    return Scaffold(
      appBar: AppBar(
        title: const Text('Riwayat Klaim'),
      ),
      body: const Center(
        child: Text('Daftar riwayat klaim akan ditampilkan di sini.'),
      ),
    );
  }
}
