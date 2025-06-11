import 'package:flutter/material.dart';
import 'package:mobile/data/models/merchandise/merchandise.dart';
import 'package:mobile/data/models/merchandise/penukaran_merchandise.dart';
import 'package:mobile/data/models/pembeli/ModelPembeli.dart';
import 'package:mobile/data/service/reward_service.dart';
import 'package:intl/intl.dart';

class MerchandiseScreen extends StatefulWidget {
  const MerchandiseScreen({super.key});

  @override
  State<MerchandiseScreen> createState() => _MerchandiseScreenState();
}

class _MerchandiseScreenState extends State<MerchandiseScreen> {
  late Future<List<Merchandise>> _merchandiseCatalogFuture;
  late Future<PembeliModel> _pembeliProfileFuture;
  final RewardService _rewardService = RewardService();

  int _currentPembeliPoints = PembeliModel().point ?? 0;
  final Map<int, int> _itemsToClaim = {}; // merchandiseId -> quantity

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

    _pembeliProfileFuture.then((pembeliData) {
      setState(() {
        _currentPembeliPoints = pembeliData.point ?? 0;
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
  }

  void _toggleItemClaim(Merchandise merchandise) {
    setState(() {
      if (_itemsToClaim.containsKey(merchandise.id)) {
        _itemsToClaim.remove(merchandise.id);
      } else {
        _itemsToClaim[merchandise.id] = 1;
      }
    });
  }

  void _increaseQuantity(int merchId) {
    setState(() {
      _itemsToClaim.update(merchId, (value) => value + 1);
    });
  }

  void _decreaseQuantity(int merchId) {
    setState(() {
      if (_itemsToClaim[merchId]! > 1) {
        _itemsToClaim.update(merchId, (value) => value - 1);
      } else {
        _itemsToClaim.remove(merchId);
      }
    });
  }

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
                TextSpan(text: 'Anda akan menukarkan '),
                TextSpan(
                  text: '${merchandise.namaMerchandise}',
                  style: const TextStyle(fontWeight: FontWeight.bold),
                ),
                TextSpan(text: ' dengan '),
                TextSpan(
                  text: '${merchandise.nilaiPoint} Poin',
                  style: const TextStyle(
                      color: Colors.deepOrange, fontWeight: FontWeight.bold),
                ),
                TextSpan(
                    text:
                        '.\n\nPoin Anda saat ini: ${NumberFormat('#,###').format(_currentPembeliPoints)}'),
                TextSpan(
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

    if (confirm == true) {
      try {
        await _rewardService.claimMerchandise([
          {'id_merchandise': merchandise.id, 'jumlah': 1}
        ]);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Klaim merchandise berhasil!')),
        );
        _loadData();
      } catch (e) {
        print('Error claiming merchandise: $e');
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal klaim merchandise: $e')),
        );
      }
    }
  }

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
    List<Merchandise> selectedMerchandiseDetails = [];

    try {
      List<Merchandise> catalog = await _merchandiseCatalogFuture;
      _itemsToClaim.forEach((merchId, quantity) {
        final merch = catalog.firstWhere((m) => m.id == merchId);
        totalPointsNeeded += merch.nilaiPoint * quantity;
        itemsPayload.add({'id_merchandise': merchId, 'jumlah': quantity});
        selectedMerchandiseDetails.add(merch);
      });
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(
            content:
                Text('Gagal mendapatkan detail merchandise untuk klaim: $e')),
      );
      return;
    }

    if (_currentPembeliPoints < totalPointsNeeded) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(
            content: Text('Poin Anda tidak cukup untuk klaim semua item ini!')),
      );
      return;
    }

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
                  ...selectedMerchandiseDetails.map((merch) {
                    final qty = _itemsToClaim[merch.id] ?? 1;
                    return Text(
                        '- ${merch.namaMerchandise} x $qty (${merch.nilaiPoint * qty} Poin)');
                  }),
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

    if (confirm == true) {
      try {
        await _rewardService.claimMerchandise(itemsPayload);
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Klaim merchandise berhasil!')),
        );
        setState(() {
          _itemsToClaim.clear();
        });
        _loadData();
      } catch (e) {
        print('Error claiming merchandise: $e');
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text('Gagal klaim merchandise: $e')),
        );
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
            icon: const Icon(Icons.history),
            onPressed: () {
              Navigator.of(context).push(
                MaterialPageRoute(
                    builder: (context) => const MyClaimsHistoryScreen()),
              );
            },
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
                    )
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
                label: Builder(builder: (context) {
                  final pointsTotal = _itemsToClaim.entries.fold<int>(
                    0,
                    (sum, entry) {
                      final merchId = entry.key;
                      final qty = entry.value;
                      // cari poin merchandise dari katalog cached
                      // Note: kita pastikan Future sudah complete saat ini, jadi pake FutureBuilder utk data katalog di atas
                      // Jadi di sini kita dapat akses snapshot data katalog?
                      // Sebagai solusi, kita gunakan snapshot.data dari builder di atas,
                      // tapi kita di luar builder sehingga kita perlu simpan data katalog di state.
                      // Untuk kesederhanaan, kita hitung total poin di widget MerchandiseCard & setState di _itemsToClaim.
                      // Jadi untuk button ini, hitung ulang poin di sini dari _itemsToClaim dan data katalog.
                      return sum; // sementara, hitung ulang di sini nanti
                    },
                  );

                  // Jadi untuk simplifikasi: hitung ulang total poin dengan cara memanggil Future secara sync? Tidak bisa.
                  // Solusi praktis: kita simpan catalog di state saat Future selesai di load.
                  return Text('Klaim ${_itemsToClaim.length} Item Terpilih');
                }),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.green[700],
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
    final canClaim = merchandise.jumlah > 0 &&
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
                color: canClaim ? Colors.green[700] : Colors.grey[600],
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
                onPressed: canClaim ? () => onClaimSingle(merchandise) : null,
                style: ElevatedButton.styleFrom(
                  backgroundColor: canClaim ? Colors.green : Colors.grey,
                  minimumSize: const Size.fromHeight(36),
                  shape: RoundedRectangleBorder(
                      borderRadius: BorderRadius.circular(6)),
                ),
                child: const Text('Klaim'),
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
