import zipfile
from xml.etree import ElementTree as ET
path = r'D:\Labs\project\ct275-project\Các bước lập dự án.docx'
with zipfile.ZipFile(path) as z:
    xml = z.read('word/document.xml')
    root = ET.fromstring(xml)
    ns = {'w':'http://schemas.openxmlformats.org/wordprocessingml/2006/main'}
    print(''.join((t.text or '') for t in root.findall('.//w:t', ns)))
