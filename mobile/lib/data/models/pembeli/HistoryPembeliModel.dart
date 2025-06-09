import 'package:mobile/Pembeli/history_pembeli.dart';

class HistoryPembeliModel {
  final int? id;
  final int? id_pembeli;
  final double? total_harga_pembelian;
  final String? status;

  HistoryPembeliModel({
    this.id,
    this.id_pembeli,
    this.total_harga_pembelian,
    this.status,
  });

  factory HistoryPembeliModel.fromJson(Map<String, dynamic> json) =>
      HistoryPembeliModel(
        id: json['id'],
        id_pembeli: json['id_pembeli'],
        total_harga_pembelian: json['total_harga_pembelian'],
        status: json['status'],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "id_pembeli": id_pembeli,
        "total_harga_pembelian": total_harga_pembelian,
        "status": status
      };
}

class HistoryPembeliResponseModel {
  final List<HistoryPembeliModel>? data;

  HistoryPembeliResponseModel({this.data});

  factory HistoryPembeliResponseModel.fromJson(Map<String, dynamic> json) =>
      HistoryPembeliResponseModel(
        data: json["data"] == null
            ? []
            : List<HistoryPembeliModel>.from(
                json["data"]!.map(
                  (x) => HistoryPembeliModel.fromJson(x),
                ),
              ),
      );

  Map<String, dynamic> toJson() => {
        "data":
            data == null ? [] : List<dynamic>.from(data!.map((x) => x.toJson()))
      };
}
