import dbf
import mysql.connector
from datetime import datetime
import os

# dbf ke mysql
# Konfigurasi
DBF_PATH = r'G:\project_python\data.dbf'
MYSQL_CONFIG = {
    'host': 'localhost',
    'user': 'root',
    'password': '',
    'database': 'dam'
}
def log_to_file(msg):
    with open("sync_log.txt", "a", encoding="utf-8") as f:
        f.write(f"[{datetime.now()}] {msg}\n")

def get_column_mapping():
    """Mapping kolom dari DBF ke MySQL"""
    return {
        'NOREG': 'kode_aduan',
        'NAMAL': 'nama',
        'ALAMATL': 'alamat',
        'TGLADU': 'date',
        'STATUS': 'status',
        'JADU': 'created_at',
        'TINDAK': 'keterangan_admin',
        'TGSELESAI': 'waktu_konfirmasi',
        'HP': 'nomor_handphone',
        'NOPER': 'nomor_saluran',
        'LAIN1': 'isi_aduan',
        'EDSELES' :'keterangan_admin'
    }

# 🧹 Normalisasi data (untuk string & tanggal)
def convert_value(value):
    if isinstance(value, datetime):
        return value.strftime('%Y-%m-%d %H:%M:%S')
    elif isinstance(value, str):
        return value.strip()
    else:
        return value

# 🚀 Sinkronisasi insert + update
def sync_from_dbf(dbf_path, mysql_config):
    try:
        table = dbf.Table(dbf_path, codepage='cp1252')  # ✅ penting untuk karakter non-ascii
        table.open()

        column_mapping = get_column_mapping()
        valid_fields = [f for f in table.field_names if f in column_mapping]

        conn = mysql.connector.connect(**mysql_config)
        cursor = conn.cursor()

        inserted = 0
        updated = 0

        for record in table:
            data = {}
            for dbf_field in valid_fields:
                mysql_field = column_mapping[dbf_field]
                value = getattr(record, dbf_field)
                data[mysql_field] = convert_value(value)

            # Siapkan bagian SQL
            columns = ', '.join(f"`{k}`" for k in data.keys())
            placeholders = ', '.join(['%s'] * len(data))
            update_clause = ', '.join(
                f"`{k}` = VALUES(`{k}`)" for k in data.keys() if k not in ['kode_aduan', 'date', 'isi_aduan']
            )

            sql = f"""
            INSERT INTO complaints ({columns})
            VALUES ({placeholders})
            ON DUPLICATE KEY UPDATE {update_clause}
            """

            cursor.execute(sql, list(data.values()))

            # Tentukan apakah data baru atau update (berdasarkan rowcount)
            if cursor.rowcount == 1:
                inserted += 1
            elif cursor.rowcount == 2:
                updated += 1

        conn.commit()
        print(f"✅ Sinkronisasi selesai:")
        print(f"   ➕ Ditambahkan : {inserted} baris")
        print(f"   🔁 Diupdate    : {updated} baris")

    except Exception as e:
        print(f"❌ ERROR: {e}")
        if 'conn' in locals():
            conn.rollback()
    finally:
        if 'table' in locals():
            table.close()
        if 'conn' in locals():
            conn.close()

# 🏁 Mulai program
if __name__ == '__main__':
    sync_from_dbf(DBF_PATH, MYSQL_CONFIG)