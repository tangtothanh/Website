DO $$
BEGIN
    IF NOT EXISTS (SELECT 1 FROM CUA_HANG WHERE CH_TEN = 'Tiệm Bánh Hương') THEN
        INSERT INTO CUA_HANG (CH_TEN, CH_DIACHI, CH_QUANHUYEN, CH_THANHPHO, CH_TRANGTHAI) VALUES
        ('Tiệm Bánh Hương', '123 Đường A, Phường B', 'Quận 1', 'Hồ Chí Minh', true);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM CUA_HANG WHERE CH_TEN = 'Quán Trà Sữa Lan') THEN
        INSERT INTO CUA_HANG (CH_TEN, CH_DIACHI, CH_QUANHUYEN, CH_THANHPHO, CH_TRANGTHAI) VALUES
        ('Quán Trà Sữa Lan', '45 Phố X, Khu Y', 'Quận 3', 'Hồ Chí Minh', true);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM CUA_HANG WHERE CH_TEN = 'Cà Phê Sáng') THEN
        INSERT INTO CUA_HANG (CH_TEN, CH_DIACHI, CH_QUANHUYEN, CH_THANHPHO, CH_TRANGTHAI) VALUES
        ('Cà Phê Sáng', '78 Đường Y, Phường Z', 'Quận 5', 'Hồ Chí Minh', false);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM SAN_PHAM WHERE SP_TEN = 'Bánh Chuối') THEN
        INSERT INTO SAN_PHAM (SP_TEN, SP_GIA, SP_MOTA, SP_HINH, L_MA) VALUES
        ('Bánh Chuối', 25000.00, 'Bánh chuối thơm ngon', 'banh_chuoi.png', 1);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM SAN_PHAM WHERE SP_TEN = 'Trà Sữa Truyền Thống') THEN
        INSERT INTO SAN_PHAM (SP_TEN, SP_GIA, SP_MOTA, SP_HINH, L_MA) VALUES
        ('Trà Sữa Truyền Thống', 30000.00, 'Trà sữa chuẩn vị', 'tra_sua.png', 2);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM SAN_PHAM WHERE SP_TEN = 'Cà Phê Đen') THEN
        INSERT INTO SAN_PHAM (SP_TEN, SP_GIA, SP_MOTA, SP_HINH, L_MA) VALUES
        ('Cà Phê Đen', 20000.00, 'Cà phê rang xay', 'ca_phe.png', 3);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM SAN_PHAM WHERE SP_TEN = 'Trà Cam') THEN
        INSERT INTO SAN_PHAM (SP_TEN, SP_GIA, SP_MOTA, SP_HINH, L_MA) VALUES
        ('Trà Cam', 25000.00, 'Trà cam tươi mát', 'tra_cam.png', 4);
    END IF;

    IF NOT EXISTS (SELECT 1 FROM KHACH_HANG WHERE KH_EMAIL = 'a@example.com') THEN
        INSERT INTO KHACH_HANG (KH_TEN, KH_EMAIL, KH_SDT, KH_DIACHI, KH_MATKHAU) VALUES
        ('Nguyễn Văn A', 'a@example.com', '0900000001', 'Hồ Chí Minh', 'password');
    END IF;

    IF NOT EXISTS (SELECT 1 FROM KHACH_HANG WHERE KH_EMAIL = 'b@example.com') THEN
        INSERT INTO KHACH_HANG (KH_TEN, KH_EMAIL, KH_SDT, KH_DIACHI, KH_MATKHAU) VALUES
        ('Trần Thị B', 'b@example.com', '0900000002', 'Hà Nội', 'password');
    END IF;

    IF NOT EXISTS (SELECT 1 FROM QUAN_TRI_VIEN WHERE QTV_TENDN = 'admin') THEN
        INSERT INTO QUAN_TRI_VIEN (QTV_TENDN, QTV_MATKHAU) VALUES ('admin', 'adminpass');
    END IF;
END$$;

DO $$
BEGIN
    -- Order for Nguyễn Văn A (2 x Bánh Chuối)
    IF NOT EXISTS (SELECT 1 FROM DON_HANG WHERE DH_TENKH = 'Nguyễn Văn A' AND DH_SDT = '0900000001') THEN
        INSERT INTO DON_HANG (KH_MA, DH_TENKH, DH_SDT, DH_DIACHI, DH_HTNHAN, CH_MA, DH_TONGTIEN, DH_TRANGTHAI)
        VALUES (
            (SELECT KH_MA FROM KHACH_HANG WHERE KH_EMAIL = 'a@example.com' LIMIT 1),
            'Nguyễn Văn A', '0900000001', 'Hồ Chí Minh', 'Giao tận nơi',
            (SELECT CH_MA FROM CUA_HANG WHERE CH_TEN = 'Tiệm Bánh Hương' LIMIT 1),
            50000.00, 'completed'
        );
    END IF;

    -- Insert order item if missing
    IF NOT EXISTS (
        SELECT 1 FROM CHI_TIET_DON_HANG c
        JOIN DON_HANG d ON c.DH_MA = d.DH_MA
        JOIN SAN_PHAM s ON c.SP_MA = s.SP_MA
        WHERE d.DH_TENKH = 'Nguyễn Văn A' AND s.SP_TEN = 'Bánh Chuối'
    ) THEN
        INSERT INTO CHI_TIET_DON_HANG (DH_MA, SP_MA, CTDH_SOLUONG, CTDH_GIA, CTDH_THANHTIEN)
        SELECT d.DH_MA, s.SP_MA, 2, s.SP_GIA, 2*s.SP_GIA
        FROM DON_HANG d, SAN_PHAM s
        WHERE d.DH_TENKH = 'Nguyễn Văn A' AND s.SP_TEN = 'Bánh Chuối'
        LIMIT 1;
    END IF;

    -- Order for Trần Thị B (1 x Trà Sữa)
    IF NOT EXISTS (SELECT 1 FROM DON_HANG WHERE DH_TENKH = 'Trần Thị B' AND DH_SDT = '0900000002') THEN
        INSERT INTO DON_HANG (KH_MA, DH_TENKH, DH_SDT, DH_DIACHI, DH_HTNHAN, CH_MA, DH_TONGTIEN, DH_TRANGTHAI)
        VALUES (
            (SELECT KH_MA FROM KHACH_HANG WHERE KH_EMAIL = 'b@example.com' LIMIT 1),
            'Trần Thị B', '0900000002', 'Hà Nội', 'Nhận tại quán',
            (SELECT CH_MA FROM CUA_HANG WHERE CH_TEN = 'Quán Trà Sữa Lan' LIMIT 1),
            30000.00, 'pending'
        );
    END IF;

    IF NOT EXISTS (
        SELECT 1 FROM CHI_TIET_DON_HANG c
        JOIN DON_HANG d ON c.DH_MA = d.DH_MA
        JOIN SAN_PHAM s ON c.SP_MA = s.SP_MA
        WHERE d.DH_TENKH = 'Trần Thị B' AND s.SP_TEN = 'Trà Sữa Truyền Thống'
    ) THEN
        INSERT INTO CHI_TIET_DON_HANG (DH_MA, SP_MA, CTDH_SOLUONG, CTDH_GIA, CTDH_THANHTIEN)
        SELECT d.DH_MA, s.SP_MA, 1, s.SP_GIA, s.SP_GIA
        FROM DON_HANG d, SAN_PHAM s
        WHERE d.DH_TENKH = 'Trần Thị B' AND s.SP_TEN = 'Trà Sữa Truyền Thống'
        LIMIT 1;
    END IF;
END$$;
