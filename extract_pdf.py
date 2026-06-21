import re
from pathlib import Path
path = Path('Tieuchiduan_n3sv.pdf')
if not path.exists():
    print('MISSING PDF', path)
    raise SystemExit(1)
text = []
try:
    import PyPDF2
    with path.open('rb') as f:
        reader = PyPDF2.PdfReader(f)
        for page in reader.pages:
            page_text = page.extract_text()
            if page_text:
                text.append(page_text)
except Exception:
    data = path.read_bytes()
    for m in re.finditer(br'stream\r?\n(.*?)endstream', data, re.S):
        try:
            st = m.group(1).decode('latin1')
        except Exception:
            continue
        for t in re.findall(r'\(([^)]*)\)', st):
            text.append(t)
if text:
    out = '\n'.join(text)
    print(out)
else:
    print('NO TEXT EXTRACTED')
