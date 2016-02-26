#!/usr/bin/python  
# -*- encoding: UTF-8 -*-  

import sys, os, time
import qrcode
from PIL import Image,ImageDraw,ImageFont

#----------------------------------------------------------------------

def gen_logo(version, mypath):
    """
	生成log图
	"""
    img = Image.new('L', (256, 256), 255)
    draw = ImageDraw.Draw(img)
    
    font = ImageFont.truetype(mypath + 'FZY4JW.TTF', 42)
    text = 'HotBlood'
    draw.text((25, 25), text, 100, font=font)    
    
    font = ImageFont.truetype(mypath + 'FZY4JW.TTF', 27)
    ver2 = version.split(".")
    text = ver2[0] + "-" + ver2[1] + "-" + ver2[2] + " " + ver2[3] + ":" + ver2[4]
    draw.text((25, 200), text, 100, font=font)    
    
    img.save(mypath + 'logo.png')
        

def gen_qrcode(string, mypath):  
    """ 
    生成中间带logo的二维码 
    需要安装qrcode, PIL库 
 
    :param string: 二维码字符串 
    :param path: 生成的二维码保存路径
    :param logo: logo文件路径 
    :return: 
    """  
    qr = qrcode.QRCode(  
        version=2,  
        error_correction=qrcode.constants.ERROR_CORRECT_H,  
        box_size=10,  
        border=1  
    )  
    qr.add_data(string)  
    qr.make(fit=True)  
  
    img = qr.make_image()  
    img = img.convert("RGBA")  
  
    if os.path.exists(mypath + 'logo.png'):  
        icon = Image.open(mypath + 'logo.png')  
        img_w, img_h = img.size  
        factor = 4  
        size_w = int(img_w / factor)  
        size_h = int(img_h / factor)  
  
        icon_w, icon_h = icon.size  
        if icon_w > size_w:  
            icon_w = size_w  
        if icon_h > size_h:  
            icon_h = size_h  
        icon = icon.resize((icon_w, icon_h), Image.ANTIALIAS)  
  
        w = int((img_w - icon_w) / 2)  
        h = int((img_h - icon_h) / 2)  
        icon = icon.convert("RGBA")  
        img.paste(icon, (w, h), icon)
        
    img.save(mypath + 'qr.png')  
  
if __name__ == "__main__":
    if len(sys.argv) < 2:
        print "No version specified."
        sys.exit(-1)
        
    mypath = os.path.split(os.path.realpath(__file__))[0] + '/'
    
    gen_logo(sys.argv[1], mypath)
    gen_qrcode("https://download_address/testing/rexue/index.php?v=" + sys.argv[1], mypath)
    

    
