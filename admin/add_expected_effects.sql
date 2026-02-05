-- Ajout du champ expected_effects à la table upcoming_litters
-- À exécuter dans phpMyAdmin

ALTER TABLE upcoming_litters 
ADD COLUMN expected_effects VARCHAR(255) NULL AFTER expected_colors;
