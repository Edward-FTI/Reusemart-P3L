class PembeliModel {
  final int? id;
  final String? nama_pembeli;
  final String? email;
  final double? saldo;
  final String? no_hp;
  final int? point;

  PembeliModel(
      {this.id,
      this.nama_pembeli,
      this.email,
      this.saldo,
      this.no_hp,
      this.point});

  factory PembeliModel.fromJson(Map<String, dynamic> json) => PembeliModel(
        id: json['id'],
        nama_pembeli: json['nama'],
        email: json['email'],
        saldo: (json['saldo'] is int)
            ? (json['saldo'] as int).toDouble()
            : json['saldo'] as double?,
        no_hp: json['no_hp'],
        point: json['point'],
      );

  Map<String, dynamic> toJson() => {
        "id": id,
        "nama_pembeli": nama_pembeli,
        "email": email,
        "saldo": saldo,
        "no_hp": no_hp,
        "point": point,
      };
}

class PembeliResponeModel {
  final List<PembeliModel>? data;

  PembeliResponeModel({this.data});

  factory PembeliResponeModel.fromJson(Map<String, dynamic> json) =>
      PembeliResponeModel(
        data: json["data"] == null
            ? []
            : List<PembeliModel>.from(
                json["data"]!.map((x) => PembeliModel.fromJson(x))),
      );

  Map<String, dynamic> toJson() => {
        "data":
            data == null ? [] : List<dynamic>.from(data!.map((x) => x.toJson()))
      };
}
