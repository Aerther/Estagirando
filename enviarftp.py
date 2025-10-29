from ftplib import FTP
import os

# 🔧 CONFIGURAÇÕES FTP
FTP_HOST = "ftp.billyorg.com"
FTP_USER = "u157320114.grupo1"
FTP_PASS = "G1@billy123"
REMOTE_ROOT = "/"  # pasta raiz remota onde o conteúdo será copiado

# Pastas que NÃO devem ser enviadas
PASTAS_IGNORADAS = {"node_modules", ".git", "__pycache__"}

# Conecta ao servidor FTP
ftp = FTP(FTP_HOST)
ftp.login(FTP_USER, FTP_PASS)

# Pasta local atual (onde o script foi executado)
LOCAL_ROOT = os.getcwd()

def garantir_pasta_remota(path):
    """Garante que todas as pastas do caminho remoto existam."""
    pastas = path.strip("/").split("/")
    caminho = ""
    for p in pastas:
        caminho += f"/{p}"
        try:
            ftp.mkd(caminho)
            print(f"[+] Criada pasta remota: {caminho}")
        except Exception:
            # Já existe
            pass

def enviar_estrutura():
    """Percorre todos os arquivos e envia, criando as pastas conforme necessário, ignorando pastas."""
    for raiz, pastas, arquivos in os.walk(LOCAL_ROOT):
        # Remove do loop as pastas que queremos ignorar
        pastas[:] = [p for p in pastas if p not in PASTAS_IGNORADAS]

        # Caminho relativo da pasta atual em relação à raiz local
        rel_path = os.path.relpath(raiz, LOCAL_ROOT)
        if rel_path == ".":
            rel_path = ""
        remote_path = os.path.join(REMOTE_ROOT, rel_path).replace("\\", "/")

        garantir_pasta_remota(remote_path)
        ftp.cwd(remote_path)

        # Envia todos os arquivos da pasta atual
        for nome in arquivos:
            caminho_local = os.path.join(raiz, nome)
            with open(caminho_local, "rb") as f:
                ftp.storbinary(f"STOR {nome}", f)
                print(f"📤 Enviado: {remote_path}/{nome}")

# 🚀 Enviar tudo
enviar_estrutura()

ftp.quit()
print("\n✅ Cópia completa — estrutura local replicada no FTP (ignorando pastas).")
