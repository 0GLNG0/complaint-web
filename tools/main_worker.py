import threading
import time
from dbf_sync import ambil_log_mysql, process_logs, log_to_file
from mysql_sync import sync_from_dbf

def worker_mysql_to_dbf():
    log_to_file("🟢 Worker A (MySQL → DBF) aktif.")
    while True:
        try:
            logs = ambil_log_mysql()
            if logs:
                log_to_file(f"🚨 A: {len(logs)} log ditemukan")
                process_logs(logs)
            else:
                log_to_file("✅ A: Tidak ada log")
        except Exception as e:
            log_to_file("❌ ERROR A:\n" + str(e))
        time.sleep(5)

def worker_dbf_to_mysql():
    log_to_file("🟢 Worker B (DBF → MySQL) aktif.")
    while True:
        try:
            sync_from_dbf()
            log_to_file("🔁 B: DBF → MySQL sinkronisasi selesai")
        except Exception as e:
            log_to_file("❌ ERROR B:\n" + str(e))
        time.sleep(5)

if __name__ == '__main__':
    threading.Thread(target=worker_mysql_to_dbf).start()
    threading.Thread(target=worker_dbf_to_mysql).start()
