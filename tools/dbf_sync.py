import dbf
import mysql.connector
import json
from datetime import datetime
import os
import sys

# ======== KONFIGURASI ========
# DBF_PATH = r'G:\aduan (backup)\data.dbf'
DBF_PATH = r'G:\project_python\data.dbf'
MYSQL_CONFIG = {
    'host': '127.0.0.1',
    'user': 'root',
    'password': '',
    'database': 'dam',
    'port' : '3306'
}

# ======== LOGGING ========
def log_to_file(msg):
    with open("sync_log.txt", "a", encoding="utf-8") as f:
        f.write(f"[{datetime.now()}] {msg}\n")

# ======== UTILITIES ========
def convert_date(value):
    try:
        if not value:
            return None
        if isinstance(value, datetime):
            return value.date()
        if isinstance(value, str):
            return datetime.fromisoformat(value.replace('Z', '')).date()
    except:
        return None

def truncate_string(value, max_len):
    if value is None:
        return ''
    return str(value)[:max_len]

# ======== AMBIL LOG ========
def ambil_log_mysql():
    conn = mysql.connector.connect(**MYSQL_CONFIG)
    cursor = conn.cursor(dictionary=True)
    cursor.execute("SELECT * FROM dbf_logs WHERE status = 'pending'")
    hasil = cursor.fetchall()
    cursor.close()
    conn.close()
    return hasil

# ======== TANDAI LOG ========
def tandai_log(log_id, status, error_msg=None):
    conn = mysql.connector.connect(**MYSQL_CONFIG)
    cursor = conn.cursor()
    if error_msg:
        cursor.execute("UPDATE dbf_logs SET status=%s, error=%s, updated_at=NOW() WHERE id=%s", (status, error_msg, log_id))
    else:
        cursor.execute("UPDATE dbf_logs SET status=%s, updated_at=NOW() WHERE id=%s", (status, log_id))
    conn.commit()
    cursor.close()
    conn.close()

# ======== KONVERSI KE FORMAT DBF ========
def build_row(data):
    return {
        'NOREG': int(data.get('NOREG', 0)),
        'NAMAL': truncate_string(data.get('NAMAL'), 50),
        'ALAMATL': truncate_string(data.get('ALAMATL'), 120),
        'TGLADU': convert_date(data.get('TGLADU')),
        'JADU': truncate_string(data.get('JADU'), 10),
        'HP': truncate_string(data.get('HP'), 30),
        'EDSELES': truncate_string(data.get('EDSELES'), 200),
        'STATUS': truncate_string(data.get('STATUS'), 10),
        'LAIN1': truncate_string(data.get('LAIN1'), 250),
        'LAIN2': truncate_string(data.get('LAIN2'), 100),
        'ASALADU': truncate_string(data.get('ASALADU'), 15),
    }

# ======== PROSES LOG ========
def process_logs(logs):
    table = dbf.Table(DBF_PATH, codepage='cp1252')
    table.open(mode=dbf.READ_WRITE)

    for log in logs:
        try:
            data = json.loads(log['data'])
            action = log['action']
            no_reg = str(data.get('NOREG'))

            log_to_file(f"🔄 Memproses log ID {log['id']} dengan aksi: {action}")

            if action == 'insert':
                table.append(build_row(data))

            elif action == 'update':
                updated = False
                for record in table:
                    if str(record['NOREG']) == no_reg:
                        with record:
                            for k, v in build_row(data).items():
                                record[k] = v
                        updated = True
                        break
                if not updated:
                    raise Exception(f"NOREG {no_reg} tidak ditemukan saat update")

                elif action == 'delete':
                    try:
                        no_reg = str(data.get('NOREG')).lstrip("0")
                        deleted = False
                
                        for record in table:
                            record_noreg = str(record['NOREG']).lstrip("0")
                            if record_noreg == no_reg:
                                record.delete()
                                deleted = True
                                print(f"✓ Data dengan NOREG {no_reg} dihapus.")
                                break
                            
                        if not deleted:
                            raise Exception(f"NOREG {no_reg} tidak ditemukan di DBF!")
                
                    except Exception as e:
                        raise Exception(f"Delete error: {e}")


            tandai_log(log['id'], 'done')
            print(f"✅ Berhasil {action} ID log {log['id']}")
            log_to_file(f"✅ Berhasil {action} ID log {log['id']}")

        except Exception as e:
            tandai_log(log['id'], 'failed', str(e))
            print(f"❌ Gagal {log['action']} ID {log['id']}: {e}")
            log_to_file(f"❌ ERROR log {log['id']}: {str(e)}")

    table.close()

# ======== MAIN ========
if __name__ == '__main__':
    logs = ambil_log_mysql()
    if not logs:
        print("📭 Tidak ada log yang perlu diproses.")
        log_to_file("📭 Tidak ada log yang perlu diproses.")
    else:
        log_to_file(f"🚀 Memproses {len(logs)} log dari MySQL")
        process_logs(logs)
        print(f"✅ Semua log selesai diproses ({len(logs)} item).")
        log_to_file(f"✅ Semua log selesai diproses ({len(logs)} item).")
