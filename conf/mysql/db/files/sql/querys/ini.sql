/*
SELECT TABLE_NAME, TABLE_ROWS 
FROM information_schema.TABLES 
WHERE TABLE_SCHEMA = 'pokemondb';


SHOW INDEX FROM pokemon;

EXPLAIN SELECT p.*, pt.* 
FROM pokemon p 
JOIN pokemon_tipo pt ON p.numero_pokedex = pt.numero_pokedex;



SELECT t.nombre as tipo, 
       p.nombre as pokemon,
       COUNT(pmf.id_movimiento) as total_movimientos
FROM tipo t
JOIN pokemon_tipo pt ON t.id_tipo = pt.id_tipo
JOIN pokemon p ON pt.numero_pokedex = p.numero_pokedex
LEFT JOIN pokemon_movimiento_forma pmf ON p.numero_pokedex = pmf.numero_pokedex
GROUP BY t.id_tipo, p.numero_pokedex
ORDER BY t.nombre, total_movimientos DESC;


SET profiling = 1;
SELECT p.nombre,
       GROUP_CONCAT(t.nombre) as tipos,
       eb.ataque,
       eb.defensa,
       eb.especial
FROM pokemon p
JOIN pokemon_tipo pt ON p.numero_pokedex = pt.numero_pokedex
JOIN tipo t ON pt.id_tipo = t.id_tipo
JOIN estadisticas_base eb ON p.numero_pokedex = eb.numero_pokedex
GROUP BY p.numero_pokedex
HAVING COUNT(DISTINCT t.id_tipo) > 1
ORDER BY eb.ataque DESC;
SHOW PROFILES;



ALTER TABLE pokemon_tipo ADD INDEX idx_tipo_pokemon (id_tipo, numero_pokedex);
ALTER TABLE pokemon_movimiento_forma ADD INDEX idx_pokemon_mov (numero_pokedex, id_movimiento);
ALTER TABLE estadisticas_base ADD INDEX idx_pokemon_stats (numero_pokedex);

*/

/*ALTER TABLE pokemon_tipo DROP INDEX idx_tipo_pokemon;

EXPLAIN SELECT t.nombre as tipo, COUNT(p.numero_pokedex) as total_pokemon
FROM tipo t
JOIN pokemon_tipo pt ON t.id_tipo = pt.id_tipo
JOIN pokemon p ON pt.numero_pokedex = p.numero_pokedex
GROUP BY t.id_tipo
ORDER BY total_pokemon DESC;*/

/*SET profiling = 1;
EXPLAIN SELECT p.nombre, t.nombre as tipo
FROM pokemon_tipo pt
JOIN tipo t ON pt.id_tipo = t.id_tipo
JOIN pokemon p ON pt.numero_pokedex = p.numero_pokedex
WHERE pt.id_tipo = 1  -- Usando la primera parte del índice compuesto
ORDER BY pt.numero_pokedex;  -- Usando la segunda parte
SHOW PROFILES;*/


-- Lista todos los Pokémon ordenados por número de pokédex.

SELECT numero_pokedex,nombre from pokemon order by numero_pokedex;


-- Muestra todos los Pokémon de tipo Fuego
select p.nombre from pokemon p 
join pokemon_tipo pt ON  p.numero_pokedex = pt.numero_pokedex 
JOIN tipo t on pt.id_tipo = t.id_tipo 
WHERE t.nombre = 'Fuego';

-- Cuenta cuántos Pokémon hay de cada tipo

select t.nombre , count(p.numero_pokedex)from pokemon p 
join pokemon_tipo pt ON  p.numero_pokedex = pt.numero_pokedex 
JOIN tipo t on pt.id_tipo = t.id_tipo
GROUP BY t.nombre;

-- Lista los primeros 10 Pokémon con mayor ataque

SELECT p.nombre from pokemon p 
join estadisticas_base eb on p.numero_pokedex = eb.numero_pokedex 
ORDER BY eb.ataque DESC 
LIMIT 10;

-- Encuentra todos los Pokémon que pesen más de 100 kg.

SELECT p.nombre, p.peso 
from pokemon p
WHERE p.peso > 100
ORDER BY p.peso desc;

-- Muestra los Pokémon que tienen más de un tipo.


select p.nombre , t.nombre , COUNT(t.id_tipo) as numero_tipos
from pokemon p 
join pokemon_tipo pt ON  p.numero_pokedex = pt.numero_pokedex 
JOIN tipo t on pt.id_tipo = t.id_tipo
GROUP BY t.nombre, p.nombre
HAVING numero_tipos > 1;

-- Lista todos los Pokémon que pueden aprender movimientos tipo Agua mediante MO

SELECT p.nombre, fa.id_forma_aprendizaje , t.nombre 
from pokemon p
join pokemon_tipo pt 					ON p.numero_pokedex = 	pt.numero_pokedex 
join tipo t 							ON pt.id_tipo = 		t.id_tipo 
join movimiento m 						ON t.id_tipo = 			m.id_tipo 
join pokemon_movimiento_forma pmf 		on m.id_movimiento = 	pmf.id_movimiento 
join forma_aprendizaje fa 				on pmf.id_forma_aprendizaje = fa.id_forma_aprendizaje 
RIGHT join MO mo on fa.id_forma_aprendizaje = mo.id_forma_aprendizaje 
WHERE m.id_tipo = (select t2.id_tipo from tipo t2 WHERE t2.nombre = 'Agua');

-- Encuentra las evoluciones directas (pokémon origen y evolucionado) ordenadas por número de pokédex.

SELECT (select p2.nombre from pokemon p2 WHERE p2.numero_pokedex = ed.pokemon_origen) as origen, (select p3.nombre from pokemon p3 WHERE p3.numero_pokedex = ed.pokemon_evolucionado) as evolucion 
from pokemon p 
join evoluciona_de ed on p.numero_pokedex = ed.pokemon_origen;


-- Calcula el promedio de puntos de ataque, defensa y especial por cada tipo de Pokémon.

SELECT p.nombre, (sum(eb.ataque + eb.especial + eb.defensa)/3) as promedio 
from pokemon p
join estadisticas_base eb on p.numero_pokedex = eb.numero_pokedex
GROUP by p.nombre
ORDER by promedio desc; 

-- Lista los Pokémon que no evolucionan.

SELECT pne.nombre 
from pokemon_no_evolucionan pne;

-- Crea una consulta que muestre cada Pokémon con sus tipos y todos los movimientos que puede aprender (incluyendo el método de aprendizaje).


SELECT p.nombre, t.nombre as tipo_poke,fa.id_forma_aprendizaje 
from pokemon p
join pokemon_tipo pt 					ON p.numero_pokedex = 	pt.numero_pokedex 
join tipo t 							ON pt.id_tipo = 		t.id_tipo 
join movimiento m 						ON t.id_tipo = 			m.id_tipo 
join pokemon_movimiento_forma pmf 		on m.id_movimiento = 	pmf.id_movimiento 
join forma_aprendizaje fa 				on pmf.id_forma_aprendizaje = fa.id_forma_aprendizaje 
join MO mo on fa.id_forma_aprendizaje = mo.id_forma_aprendizaje ;
-- WHERE m.id_tipo = (select t2.id_tipo from tipo t2);

-- tabla materializada
CREATE TABLE materialized_pokemon AS
SELECT 
    p.numero_pokedex,
    p.nombre,
    p.peso,
    p.altura,
    e.ataque,
    e.defensa,
    e.velocidad
FROM pokemon p
LEFT JOIN estadisticas_base e ON p.numero_pokedex = e.numero_pokedex;

SELECT * from materialized_pokemon;


-- vista
CREATE OR REPLACE VIEW pokemon_base_view AS
SELECT 
    p.numero_pokedex,
    p.nombre,
    p.peso,
    p.altura,
    e.ataque,
    e.defensa,
    e.velocidad
FROM pokemon p
LEFT JOIN estadisticas_base e ON p.numero_pokedex = e.numero_pokedex;

SELECT * from pokemon_base_view;



