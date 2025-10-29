from ftplib import FTP

# 🔧 CONFIGURAÇÕES
FTP_HOST = "ftp.billyorg.com"
FTP_USER = "u157320114.grupo1"
FTP_PASS = "G1@billy123"

# Conectar ao servidor
ftp = FTP(FTP_HOST)
ftp.login(FTP_USER, FTP_PASS)

def excluir_recursivo(ftp, caminho, manter):
    """Apaga todos os arquivos e subpastas dentro do caminho remoto, exceto os do conjunto 'manter'."""
    try:
        ftp.cwd(caminho)
    except Exception:
        return

    itens = ftp.nlst()
    for item in itens:
        if item in (".", "..") or item in manter:
            continue

        try:
            # Se conseguir entrar, é pasta
            ftp.cwd(item)
            ftp.cwd("..")
            excluir_recursivo(ftp, f"{caminho}/{item}", manter)
            try:
                ftp.rmd(f"{caminho}/{item}")
                print(f"🗑️ Pasta removida: {caminho}/{item}")
            except Exception as e:
                print(f"⚠️ Não conseguiu remover pasta {item}: {e}")
        except Exception:
            # É arquivo
            if item not in manter:
                try:
                    ftp.delete(f"{caminho}/{item}")
                    print(f"❌ Arquivo removido: {caminho}/{item}")
                except Exception as e:
                    print(f"⚠️ Erro ao excluir {item}: {e}")

def limpar_tudo_exceto(manter_arquivos):
    """Remove tudo da raiz do FTP, exceto os arquivos especificados."""
    raiz = ftp.pwd()
    itens = ftp.nlst()
    for item in itens:
        if item in (".", "..") or item in manter_arquivos:
            continue
        try:
            ftp.cwd(item)
            ftp.cwd("..")
            excluir_recursivo(ftp, f"{raiz}/{item}", manter_arquivos)
            ftp.rmd(f"{raiz}/{item}")
            print(f"🗑️ Pasta removida: {item}")
        except Exception:
            if item not in manter_arquivos:
                ftp.delete(item)
                print(f"❌ Arquivo removido: {item}")

# ⚙️ Nome do(s) arquivo(s) a manter
manter = {"index.html"}

# ⚠️ Confirmação de segurança
confirmar = input(f"⚠️ Tem certeza que quer EXCLUIR TUDO do FTP exceto {', '.join(manter)}? (digite SIM): ")
if confirmar == "SIM":
    limpar_tudo_exceto(manter)
    print("\n✅ Limpeza completa (apenas os arquivos especificados foram mantidos).")
else:
    print("\n❎ Operação cancelada.")

ftp.quit()
