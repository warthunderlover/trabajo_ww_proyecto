INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'product_DSP', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));
INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'product_UPD', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));
INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'product_DEL', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));
INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'product_INS', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));

INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('CLIENTE', 'product_DSP', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));

INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('VENDEDOR', 'product_DSP', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));
INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('VENDEDOR', 'product_UPD', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));

INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Controllers\\Mantenimientos\\CInventario', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));
INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('VENDEDOR', 'Controllers\\Mantenimientos\\Inventario', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));
INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('VENDEDOR', 'Controllers\\Mantenimientos\\CInventario', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));

INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('CLIENTE', 'Controllers\\Mantenimientos\\Inventario', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));

INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('ADMIN', 'Menu_Products', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));
INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('VENDEDOR', 'Menu_Products', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));
INSERT INTO funciones_roles (rolescod, fncod, fnrolest, fnexp) VALUES ('CLIENTE', 'Menu_Products', 'ACT', DATE_ADD(NOW(), INTERVAL 1 YEAR));
