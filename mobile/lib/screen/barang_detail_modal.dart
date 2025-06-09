import 'package:flutter/material.dart';
import 'package:carousel_slider/carousel_slider.dart';
import 'package:intl/intl.dart';
import 'package:mobile/data/models/barang/barang.dart';

class BarangDetailModalSimple extends StatefulWidget {
  final Barang barang;

  const BarangDetailModalSimple({super.key, required this.barang});

  @override
  State<BarangDetailModalSimple> createState() => _BarangDetailModalSimpleState();
}

class _BarangDetailModalSimpleState extends State<BarangDetailModalSimple> {
  int _currentIndex = 0;

  @override
  Widget build(BuildContext context) {
    final List<String> images = [];

    if (widget.barang.gambar != null && widget.barang.gambar!.isNotEmpty) {
      images.add(widget.barang.fullGambarUrl);
    }
    if (widget.barang.gambarDua != null && widget.barang.gambarDua!.isNotEmpty) {
      images.add(widget.barang.fullGambarUrl.replaceFirst(widget.barang.gambar!, widget.barang.gambarDua!));
    }

    return Dialog(
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: SizedBox(
        width: double.maxFinite,
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            // Header with title and close button
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Expanded(
                    child: Text(
                      widget.barang.namaBarang,
                      style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold),
                      overflow: TextOverflow.ellipsis,
                    ),
                  ),
                  IconButton(
                    icon: const Icon(Icons.close),
                    onPressed: () => Navigator.of(context).pop(),
                  ),
                ],
              ),
            ),

            // Image slider
            if (images.isNotEmpty)
              Column(
                children: [
                  CarouselSlider(
                    options: CarouselOptions(
                      height: 220,
                      viewportFraction: 1,
                      enableInfiniteScroll: images.length > 1,
                      onPageChanged: (index, _) {
                        setState(() {
                          _currentIndex = index;
                        });
                      },
                    ),
                    items: images.map((url) {
                      return Image.network(
                        url,
                        fit: BoxFit.contain,
                        width: double.infinity,
                      );
                    }).toList(),
                  ),
                  // Simple dots indicator
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: images.asMap().entries.map((entry) {
                      return Container(
                        width: 8,
                        height: 8,
                        margin: const EdgeInsets.symmetric(horizontal: 4, vertical: 8),
                        decoration: BoxDecoration(
                          shape: BoxShape.circle,
                          color: _currentIndex == entry.key
                              ? Colors.green
                              : Colors.grey[400],
                        ),
                      );
                    }).toList(),
                  ),
                ],
              )
            else
              Container(
                height: 220,
                color: Colors.grey[300],
                child: const Center(
                  child: Icon(Icons.image_not_supported, size: 60, color: Colors.grey),
                ),
              ),

            // Detail info
            Padding(
              padding: const EdgeInsets.all(16),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(
                    'Harga: ${widget.barang.formattedHarga}',
                    style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 8),
                  if (widget.barang.statusGaransi != null && widget.barang.statusGaransi!.isNotEmpty)
                    Row(
                      children: [
                        const Icon(Icons.security, color: Colors.green, size: 18),
                        const SizedBox(width: 6),
                        Text(
                          'Garansi: ${widget.barang.statusGaransi}',
                          style: const TextStyle(fontSize: 14),
                        ),
                        if (DateTime.tryParse(widget.barang.statusGaransi!) != null)
                          Text(
                            ' (s/d ${DateFormat('dd MMM yyyy').format(DateTime.parse(widget.barang.statusGaransi!))})',
                            style: const TextStyle(fontSize: 14, fontStyle: FontStyle.italic),
                          ),
                      ],
                    )
                  else
                    const Text('Status Garansi: Tidak ada garansi', style: TextStyle(fontSize: 14, color: Colors.grey)),
                  const SizedBox(height: 12),
                  Text('Deskripsi:', style: const TextStyle(fontSize: 15, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 4),
                  Text(widget.barang.deskripsi, style: const TextStyle(fontSize: 14)),
                ],
              ),
            ),

            // Actions (close + add to cart)
            Padding(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.end,
                children: [
                  TextButton(
                    onPressed: () => Navigator.of(context).pop(),
                    child: const Text('Tutup'),
                  ),
                  const SizedBox(width: 10),
                  // ElevatedButton.icon(
                  //   icon: const Icon(Icons.add_shopping_cart),
                  //   label: const Text('Tambah ke Keranjang'),
                  //   onPressed: () {
                  //     Navigator.of(context).pop();
                  //     ScaffoldMessenger.of(context).showSnackBar(
                  //       SnackBar(content: Text('${widget.barang.namaBarang} ditambahkan ke keranjang!')),
                  //     );
                  //   },
                  //   style: ElevatedButton.styleFrom(backgroundColor: Colors.green),
                  // ),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}
