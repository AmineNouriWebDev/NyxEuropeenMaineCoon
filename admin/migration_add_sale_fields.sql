-- Migration SQL pour ajouter les fonctionnalités de vente
-- Kings: Saillie + Retraite
-- Queens: Retraite uniquement

ALTER TABLE chats
ADD COLUMN for_sale BOOLEAN DEFAULT 0 COMMENT 'Disponible à la vente',
ADD COLUMN sale_type ENUM('stud', 'retirement', 'both') NULL COMMENT 'Type de vente: saillie, retraite, ou les deux',
ADD COLUMN stud_price_cad DECIMAL(10,2) NULL COMMENT 'Prix saillie en CAD',
ADD COLUMN stud_price_usd DECIMAL(10,2) NULL COMMENT 'Prix saillie en USD',
ADD COLUMN retirement_price_cad DECIMAL(10,2) NULL COMMENT 'Prix retraite en CAD',
ADD COLUMN retirement_price_usd DECIMAL(10,2) NULL COMMENT 'Prix retraite en USD',
ADD COLUMN sale_description TEXT NULL COMMENT 'Description pour la vente';

-- Commentaire d'utilisation:
-- Pour un King en saillie: for_sale=1, sale_type='stud', remplir stud_price_*
-- Pour un King à la retraite: for_sale=1, sale_type='retirement', remplir retirement_price_*
-- Pour un King (saillie ET retraite): for_sale=1, sale_type='both', remplir tous les prix
-- Pour une Queen à la retraite: for_sale=1, sale_type='retirement', remplir retirement_price_*
