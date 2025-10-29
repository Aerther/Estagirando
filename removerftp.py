from ftplib import FTP, error_perm

FTP_HOST = "ftp.billyorg.com"
FTP_USER = "u157320114.grupo1"
FTP_PASS = "G1@billy123"

ftp = FTP(FTP_HOST)
ftp.login(FTP_USER, FTP_PASS)
ftp.set_pasv(True)  # importante!

def excluir_recursivo(ftp, caminho):
    """Apaga todos os arquivos e subpastas dentro de um caminho remoto."""
    try:
        ftp.cwd(caminho)
    except error_perm:
        return  # pasta não existe

    for item in ftp.nlst():
        if item in (".", ".."):
            continue
        full_path = f"{caminho}/{item}"
        try:
            ftp.cwd(full_path)  # tenta entrar, se conseguir é pasta
            ftp.cwd("..")
            excluir_recursivo(ftp, full_path)
            ftp.rmd(full_path)
            print(f"🗑️ Pasta removida: {full_path}")
        except error_perm:
            try:
                ftp.delete(full_path)
                print(f"❌ Arquivo removido: {full_path}")
            except error_perm:
                print(f"⚠️ Não conseguiu excluir: {full_path}")

def limpar_tudo_exceto(ftp, manter):
    """Remove tudo da raiz, exceto arquivos/pastas na lista 'manter'."""
    raiz = ftp.pwd()
    for item in ftp.nlst():
        if item in (".", "..") or item in manter:
            continue

        full_path = f"{raiz}/{item}"
        try:
            ftp.cwd(item)  # tenta entrar → é pasta
            ftp.cwd("..")
            excluir_recursivo(ftp, full_path)
            ftp.rmd(full_path)
            print(f"🗑️ Pasta removida: {item}")
        except error_perm:
            try:
                ftp.delete(full_path)
                print(f"❌ Arquivo removido: {item}")
            except error_perm:
                print(f"⚠️ Não conseguiu excluir arquivo: {item}")

# --- EXECUÇÃO ---
manter = ["index.html"]  # arquivos que não serão apagados

confirmar = input("⚠️ Tem certeza que quer excluir TUDO exceto index.html? (digite SIM): ")
if confirmar == "SIM":
    limpar_tudo_exceto(ftp, manter)
    print("✅ Limpeza concluída.")
else:
    print("❎ Cancelado.")

ftp.quit()
