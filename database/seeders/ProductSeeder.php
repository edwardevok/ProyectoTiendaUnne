<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

/**
 * Carga el catalogo completo de productos de la Tienda UNNE (36 productos).
 * Las imagenes ya estan incluidas en el repositorio bajo public/img/ y public/img/productos/,
 * por lo que el campo 'image' guarda unicamente el nombre del archivo.
 *
 * Las categorias se resuelven por NOMBRE (no por id fijo) usando las que crea CategoriaSeeder,
 * por eso este seeder debe ejecutarse despues de CategoriaSeeder.
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // name => id de las categorias ya creadas por CategoriaSeeder.
        $categorias = Category::pluck('id', 'name');

        foreach ($this->productos() as $p) {
            $nombreCategoria = $p['category'];
            unset($p['category']);
            $p['category_id'] = $categorias[$nombreCategoria] ?? null;

            Product::create($p);
        }
    }

    private function productos(): array
    {
        return [
            [
                'name'        => 'Anteojos de Sol',
                'description' => 'Cuidá tu vista con todo el estilo de la UNNE. ☀️ Ya sea para caminar entre facultades en las tardes correntinas y chaqueñas o para meter facha el finde, estos lentes de sol son el accesorio definitivo. Diseño urbano, mate, súper liviano y con el logo oficial grabado con sutil elegancia en la patilla. Sumalos a tu look diario y llevá los colores de tu universidad a todos lados.',
                'price'       => 69000,
                'stock'       => 5,
                'category'    => 'Accesorios',
                'image'       => 'prod_6a30cdd0465d80.00633146.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Llavero Metálico Institucional',
                'description' => 'Que no se te pierdan las llaves de casa (ni del auto) a mitad de cuatrimestre. 🔑 Este llavero premium de metal pulido combina resistencia y un diseño impecable con el sol de la UNNE calado en detalle. Ideal para colgar en tu mochila o llevar en el bolsillo sin que ocupe espacio de más. Un clásico que todo estudiante o egresado orgulloso tiene que tener.',
                'price'       => 9500,
                'stock'       => 5,
                'category'    => 'Accesorios',
                'image'       => 'prod_6a30ce24596219.94050311.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Mochila Universitaria Ergonómica',
                'description' => 'Tu compañera infatigable de cursada. 🎒 Diseñada pensando en la vida universitaria real: espacio acolchado para la notebook, múltiples bolsillos con cierres reforzados para los apuntes y redes laterales para tu botella. Con un diseño negro total ultra combinable y el escudo de la UNNE al frente, es cómoda, ergonómica y súper resistente. Meté todo lo que necesitás para el día y salí a comerte el mundo.',
                'price'       => 64000,
                'stock'       => 4,
                'category'    => 'Accesorios',
                'image'       => 'prod_6a30ce58231794.76692762.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Paraguas UNNE Urban',
                'description' => 'Que una lluvia sorpresa no te arruine el día de examen. 🌧️ Este paraguas institucional en azul profundo combina estilo y máxima protección. Con estructura reforzada para aguantar el viento y apertura práctica, lleva impreso el escudo oficial de la universidad. Entrá al campus impecable, sin importar cómo esté el clima afuera.',
                'price'       => 26000,
                'stock'       => 4,
                'category'    => 'Accesorios',
                'image'       => 'prod_6a30ce7d97fb36.35519121.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Pin Universitario',
                'description' => 'Llevá tu orgullo UNNE directo en el pecho. 🎓 Este pin metálico esmaltado en azul y dorado es el detalle perfecto para personalizar tu campera, mochila o usar en eventos institucionales y colaciones. Compacto, elegante y con un agarre seguro para que no se te caiga nunca. El verdadero "pequeño gran detalle" de pertenencia.',
                'price'       => 5000,
                'stock'       => 2,
                'category'    => 'Accesorios',
                'image'       => 'prod_6a30cebe32a806.87090148.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Pulsera Textil',
                'description' => 'Onda urbana y ADN universitario en tu muñeca. 🧵 Hecha de material textil trenzado súper resistente al agua y al uso diario, esta pulsera combina sutilmente los colores y las siglas de la UNNE. Cuenta con un broche metálico ajustable de alta calidad. Es ligera, canchera y perfecta para usar 24/7. ¡Llevá la comunidad con vos a donde vayas!',
                'price'       => 6500,
                'stock'       => 5,
                'category'    => 'Accesorios',
                'image'       => 'prod_6a30cee9c8da39.50932208.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Tarjetero con Cordón',
                'description' => 'Tu Identidad UNNE siempre a mano (y con mucha onda). 🪪 Ideal para tener tu credencial de la facu, tarjeta de colectivo o pase tecnológico siempre listos y visibles. Incluye un protector rígido azul y un cordón reforzado estampado con el logo oficial. Olvidate de registrar toda la mochila buscando las tarjetas antes de entrar a clases.',
                'price'       => 12000,
                'stock'       => 6,
                'category'    => 'Accesorios',
                'image'       => 'prod_6a30cf1019c8e2.36666098.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Tote Bag Ecológica',
                'description' => 'Estilo sustentable para el día a día. 🌿 Esta tote bag de lienzo natural es perfecta para quienes buscan practicidad sin perder la onda. Espaciosa, cómoda para colgar al hombro y con un estampado de alto contraste del sol de la UNNE. Ideal para llevar tus apuntes a la biblioteca, hacer las compras después de clase o meter el equipo de mate para el parque.',
                'price'       => 14900,
                'stock'       => 5,
                'category'    => 'Bazar',
                'image'       => 'prod_6a30cf710442a3.18698719.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Botella/Termo Térmico Minimalista',
                'description' => 'Hidratación y concentración al 100%. 💧 Mantené tu agua bien fría para aguantar las clases de verano o tu café bien caliente durante las noches de estudio. Con un acabado negro mate premium y el logo de la UNNE en contraste, esta botella térmica de acero inoxidable es hermética, ligera y no transpira. Un must-have para tener arriba del banco en cada teórica.',
                'price'       => 22500,
                'stock'       => 2,
                'category'    => 'Bazar',
                'image'       => 'prod_6a30cf927f2b98.78782006.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Lunchera Térmica Institucional',
                'description' => 'Comé sano, ahorrá y metele facha a tus almuerzos. 🍱 Olvidate de andar gastando de más en el buffet. Esta lunchera térmica con correa ajustable mantiene tus viandas frescas por horas. Su diseño compacto pero espacioso en azul y detalles grises es súper cómodo para llevar de la mañana a la noche. Almorzar en los pastos del campus nunca tuvo tanto estilo.',
                'price'       => 34000,
                'stock'       => 10,
                'category'    => 'Bazar',
                'image'       => 'prod_6a30cffa317365.97664109.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Mate de Calabaza Premium',
                'description' => 'Tradición, estudio y el sentimiento de pertenecer. 🧉 El clásico de clásicos que no puede faltar en ninguna mesa de examen ni en las juntadas en el campus. Un mate de calabaza seleccionada, forrado con virola de metal labrada y el escudo histórico de la UNNE grabado a fuego en el cuerpo. Curalo con orgullo y que te acompañe en cada noche de entrega. (No incluye bombilla).',
                'price'       => 26000,
                'stock'       => 10,
                'category'    => 'Bazar',
                'image'       => 'prod_6a30d13c061a06.72161556.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Mate Térmico de Acero Inoxidable',
                'description' => 'Para los que cuelgan con el mate mientras codean o leen. 💻 Si sos de los que se concentran y se olvidan de tomar, este mate térmico de acero inoxidable es tu solución. Mantiene la yerba en su temperatura justa por mucho más tiempo, no necesita curado, se lava en dos segundos y tiene un diseño negro mate minimalista con el sol de la UNNE grabado en acero. Incluye bombilla de acero inoxidable de alta calidad.',
                'price'       => 31000,
                'stock'       => 10,
                'category'    => 'Bazar',
                'image'       => 'prod_6a30d18aed32a2.99388001.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Taza de Cerámica Universitaria',
                'description' => 'Tu dosis de café (o té) para arrancar el día con toda. ☕ Desayuná como un verdadero estudiante de la UNNE con esta taza de cerámica importada de alta resistencia. Diseño blanco brillante con el logo oficial en azul y dorado texturado. Soporta microondas y lavavajillas sin perder el color ni el brillo. El empujón motivacional que necesitás antes de encarar la rutina.',
                'price'       => 11500,
                'stock'       => 10,
                'category'    => 'Bazar',
                'image'       => 'prod_6a30d1a6d36de3.06750515.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Termo Térmico Black UNNE (1L)',
                'description' => 'Compañero eterno de apuntes y apuntados. 💧 Un termo de acero inoxidable de doble capa ultra resistente en color negro mate que se banca cualquier viaje en la mochila. Mantiene el agua a la temperatura perfecta para el mate o el café durante todo el día de cursada. Viene con el sello oficial de la UNNE al frente para marcar territorio en el banco de la facu. ¡Indestructible!',
                'price'       => 53000,
                'stock'       => 11,
                'category'    => 'Bazar',
                'image'       => 'prod_6a30d1c8788ee2.25561295.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Vaso Térmico Urbano con Tapa',
                'description' => 'Tu bebida favorita, siempre lista para llevar. 🏃‍♂️ Ideal para los que van corriendo de la parada del cole al aula. Este vaso térmico de acero inoxidable cuenta con tapa hermética transparente y boquilla deslizable para evitar accidentes sobre los apuntes o la notebook. Con acabado negro satinado y grabado láser de la UNNE, mantiene tus bebidas frías o calientes mientras vas de una clase a otra.',
                'price'       => 27500,
                'stock'       => 15,
                'category'    => 'Bazar',
                'image'       => 'prod_6a30d1eabcf639.99034172.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Yerbatera Metálica de Diseño',
                'description' => 'Chau paquetes rotos en la mochila. 🌿 Guardá tu yerba con estilo y practicidad en esta lata yerbatera premium de color azul profundo y logo dorado. Cuenta con tapa de rosca ultra hermética para que no se humedezca ni se vuelque nada entre los libros. El accesorio que te faltaba para completar el kit matero oficial de la universidad.',
                'price'       => 14500,
                'stock'       => 10,
                'category'    => 'Bazar',
                'image'       => 'prod_6a30d202ecd527.76707213.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Buzo Canguro Oversize Gris (L)',
                'description' => 'El uniforme oficial del estudiante en época de parciales. 🛋️ Comodidad absoluta y abrigo total. Este buzo con capucha (hoodie) está confeccionado en frisa de algodón premium color gris melange, con puños reforzados, cordón ajustable y bolsillo frontal tipo canguro. Lleva el escudo de la UNNE estampado en el pecho en una edición especial de alto relieve. Ideal para lookearte cómodo, meterle facha a la cursada y sobrevivir al aire acondicionado de la biblioteca.',
                'price'       => 69000,
                'stock'       => 5,
                'category'    => 'Indumentaria',
                'image'       => 'prod_6a30d251f212b3.69383618.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Camiseta Técnica UNNE Deportes',
                'description' => 'Sudá la camiseta de tu universidad. ⚽ Transpirable, ligera y diseñada para el alto rendimiento (o para jugar ese fútbol 5 clave con tus amigos del pabellón). Hecha con tecnología de microfibra dry-fit que expulsa el sudor, combina un azul profundo con recortes anatómicos en blanco y el escudo deportivo de la UNNE en el pecho. Ponete la de la facu y salí a ganar.',
                'price'       => 37000,
                'stock'       => 3,
                'category'    => 'Indumentaria',
                'image'       => 'prod_6a30d27016e8d7.73472508.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Campera Universitaria "Varsity" Premium (M)',
                'description' => 'La prenda definitiva de la colección. 🧥 Una campera de estilo americano clásica que combina cuerpo de paño azul marino de máxima densidad con mangas texturadas en tono crema. Cuenta con cuello y puños elásticos a rayas, botones a presión reforzados y el logo bordado en alta definición en el pecho. Estilo urbano, atemporal y con una presencia tremenda para lucir dentro y fuera de la universidad. ¡Una inversión para toda la vida!',
                'price'       => 118000,
                'stock'       => 5,
                'category'    => 'Indumentaria',
                'image'       => 'prod_6a30d291168be2.28846086.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Chaleco Inflable "Puffer" UNNE (M)',
                'description' => 'Comodidad, abrigo y cero peso. 🌬️ El chaleco puffer es la prenda comodín para esos días donde el clima no se decide. Ultra liviano, térmico y con un calce al cuerpo impecable en color azul marino. Viene con cuello alto para protegerte del viento y el logo oficial de la UNNE bordado con excelente definición en el pecho. Ponetelo arriba de un buzo o una remera y armate un lookazo universitario en un segundo.',
                'price'       => 59000,
                'stock'       => 8,
                'category'    => 'Indumentaria',
                'image'       => 'prod_6a30d31699b4c2.60299512.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Chomba de Piqué Institucional (S)',
                'description' => 'El equilibrio justo entre lo formal y lo casual. 👔 Ideal para cuando tenés que meter una presentación de proyecto, ir a un congreso o asistir a tus prácticas profesionales. Confeccionada en piqué de algodón premium que mantiene la forma lavado tras lavado, en un tono azul clásico. Cuenta con cuello tejido, cartera con botones y el escudo circular de la UNNE bordado en alta calidad. Sencilla, elegante y con toda la identidad.',
                'price'       => 34000,
                'stock'       => 5,
                'category'    => 'Indumentaria',
                'image'       => 'prod_6a30d33ba77074.06516734.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Guardapolvo / Delantal Médico UNNE (M)',
                'description' => 'Directo al laboratorio o al hospital escuela con la mejor presencia. 🥼 Un infaltable para los estudiantes de Medicina, Enfermería, Veterinaria, Bioquímica o Ciencias Exactas. Este delantal está confeccionado en tela acrocel de primera calidad (no se amarillea y es súper fácil de planchar), con costuras reforzadas, bolsillos amplios para el instrumental y martingala trasera para un mejor calce. El logo oficial de la UNNE va bordado directo sobre el bolsillo superior. ¡Arrancá tus prácticas con todo!',
                'price'       => 39500,
                'stock'       => 5,
                'category'    => 'Indumentaria',
                'image'       => 'prod_6a30d358962464.21707217.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Gorra Trucker / Caps Premium',
                'description' => 'Cuidate del sol con alta facha. 🧢 El accesorio urbano por excelencia para los días de calor en el campus. Esta gorra de gabardina premium azul oscuro tiene visera rígida moldeable y un bordado frontal imponente en hilo dorado con el sol y el nombre de la universidad. Ajustable y súper cómoda, es ideal para esos días en los que salís apurado de casa y querés meterle onda a tu outfit diario.',
                'price'       => 17500,
                'stock'       => 0,
                'category'    => 'Indumentaria',
                'image'       => 'prod_6a30d37a9aa7f8.71701801.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Remera de Algodón Classic Blue',
                'description' => 'La básica que combina con absolutamente todo. 👕 Hecha de 100% algodón peinado 24/1, suave, fresca y con una caída espectacular. En color azul marino profundo, lleva el isotipo minimalista de la UNNE estampado en el pecho en serigrafía de alta durabilidad (no se agrieta con los lavados). Ideal para usar todos los días de la semana, bancarse las jornadas largas de cursada y llevar la pertenencia universitaria a flor de piel.',
                'price'       => 21000,
                'stock'       => 3,
                'category'    => 'Indumentaria',
                'image'       => 'prod_6a30d3945913a7.59957572.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Sombrero con Mosquitero / Sombrero de Campo UNNE',
                'description' => 'El aliado definitivo para las prácticas de campo. 🪵 Pensado especialmente para los estudiantes de Agronomía, Veterinaria, Biología o carreras con actividades al aire libre en la región. Este sombrero de ala ancha color arena te protege completamente del sol y cuenta con una red mosquitera integrada de alta visibilidad para trabajar cómodo y libre de insectos. Incluye cordón de ajuste elástico y el logo de la UNNE bordado al frente. ¡Que nada te sature en el terreno!',
                'price'       => 26000,
                'stock'       => 4,
                'category'    => 'Indumentaria',
                'image'       => 'prod_6a30d3ae741d53.91497544.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Carpeta de Tres Anillos Universitaria',
                'description' => 'Ordená tus apuntes con nivel premium. 📂 Olvidate de las hojas sueltas y el caos pre-finales. Esta carpeta de tres anillos grandes está hecha de tapa dura plastificada ultra resistente y lavable. Su diseño minimalista en blanco destaca el sol de la UNNE en gran tamaño. Ideal para archivar resúmenes, separar por materias y tener todo el cuatrimestre bajo control.',
                'price'       => 15500,
                'stock'       => 10,
                'category'    => 'Librería',
                'image'       => 'prod_6a30d3dc963c98.21273948.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Cuaderno Universitario Tapa Dura (A5)',
                'description' => 'Donde nacen tus mejores ideas y resúmenes. 📓 Un cuaderno premium con hojas rayadas de alto gramaje (para que no se pase la tinta de tus resaltadores) y encuadernación cosida que permite abrirlo a 180° sin desarmarse. Con una delicada tapa dura azul marino y el logo de la UNNE grabado en relieve dorado metalizado. El compañero estético y funcional que da gusto sacar de la mochila en cada clase.',
                'price'       => 13000,
                'stock'       => 10,
                'category'    => 'Librería',
                'image'       => 'prod_6a30d3fd7ba904.56185121.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Lapicera Ejecutiva Rollerball',
                'description' => 'Para firmar las actas de examen con total confianza. ✍️ Esta lapicera de trazo fluido y secado rápido tiene un cuerpo metálico negro brillante con detalles cromados y el grabado láser de la Universidad Nacional del Nordeste. Viene con clip de sujeción para el bolsillo de la camisa o el delantal. Escritura suave, elegante y profesional para dejar marca en cada hoja.',
                'price'       => 9000,
                'stock'       => 10,
                'category'    => 'Librería',
                'image'       => 'prod_6a30d415bca705.09993262.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Lápiz Ecológico "Plantable" UNNE',
                'description' => 'Escribí el futuro y plantá una idea. 🌱 Un lápiz único hecho de materiales reciclados con mina de grafito suave para tus esquemas y bocetos. ¿Lo mejor? Cuando se vuelve demasiado corto para escribir, lo enterrás y de su cápsula verde en la punta brotan semillas para dar vida a una planta. Un detalle súper original, sustentable y con el sello de la UNNE para regalar o regalarte.',
                'price'       => 2800,
                'stock'       => 10,
                'category'    => 'Librería',
                'image'       => 'prod_6a30d42e370288.47094854.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Libreta de Notas Institucional',
                'description' => 'Tus ideas, proyectos y apuntes clave en un solo lugar. 📘 Para las reuniones de grupo, las ideas sueltas o para usar de agenda diaria. Esta libreta premium cuenta con tapa blanda de cuero ecológico azul, cinta señaladora integrada y un imponente grabado del sol de la UNNE en dorado metalizado. Tiene el tamaño justo para llevarla cómodamente en cualquier mochila o tote bag sin sumar peso de más.',
                'price'       => 11000,
                'stock'       => 10,
                'category'    => 'Librería',
                'image'       => 'prod_6a30d5c62a7449.42168525.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Mousepad Ergonómico UNNE',
                'description' => 'Fluidez total para tus horas frente a la pantalla. 💻 Ya sea que te pases la noche programando, editando o armando esa presentación eterna en Jira, este mousepad es clave. Con base de goma antideslizante para máxima estabilidad y una superficie de tela suave de alta densidad que optimiza el deslizamiento del mouse. Viene estampado con el diseño dinámico oficial en azul, blanco y dorado.',
                'price'       => 7800,
                'stock'       => 10,
                'category'    => 'Librería',
                'image'       => 'prod_6a30d62bb443d7.27470877.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Pendrive Metálico de Alta Velocidad (64GB)',
                'description' => 'Tus entregas y archivos más importantes, ultra seguros. 💾 Olvidate de quedarte sin espacio o de los pendrives plásticos que se rompen en la mochila. Este modelo ejecutivo está construido íntegramente en aluminio cepillado de alta resistencia, con el logo de la UNNE grabado con precisión láser. Con 64GB de capacidad, es perfecto para pasar instaladores, guardar bases de datos, PDFs pesados y tus entregas finales.',
                'price'       => 16900,
                'stock'       => 0,
                'category'    => 'Librería',
                'image'       => 'prod_6a30d64c4f2648.52878374.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Portalápices Ecológico Completo',
                'description' => 'Escritorio ordenado, mente enfocada. 🌿 Sumá sustentabilidad a tu espacio de estudio con este portalápices cilíndrico de cartón rígido reciclado de alta densidad. Viene con la estampa de la línea Eco Amigable de la UNNE. La mejor parte: viene completamente equipado con una tanda de lápices de grafito listos para usar en tus bocetos o diagramas. Ideal para mantener tus herramientas siempre a mano y a la vista.',
                'price'       => 9200,
                'stock'       => 5,
                'category'    => 'Librería',
                'image'       => 'prod_6a30d667086a27.96448451.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Block de Notas Adhesivas (Post-it) Eco',
                'description' => 'Que no se te pase de largo ninguna fecha clave. 📌 El aliado definitivo para marcar las páginas de los libros, dejar recordatorios en el monitor o anotar conceptos rápidos mientras repasás. Este block de notas adhesivas está confeccionado en papel 100% reciclado con un sutil tono kraft y lleva impreso el sello de la universidad. Tiene un excelente nivel de adhesión sin dañar las hojas de tus apuntes al despegarlo.',
                'price'       => 4500,
                'stock'       => 5,
                'category'    => 'Librería',
                'image'       => 'prod_6a30d67d4dd317.89009005.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Set de Lapiceras Ejecutivas (x3)',
                'description' => 'El trío definitivo para encarar cualquier entrega profesional. 🖋️ Un estuche de gala rígido que incluye tres bolígrafos metálicos premium en colores azul, plata y negro satinado, todos con detalles cromados y el grabado de la Universidad Nacional del Nordeste. Con tres opciones de tinta fluidas y ergonómicas, es el set ideal para regalar a un futuro graduado o para lucirte en tus firmas y entregas más importantes.',
                'price'       => 22500,
                'stock'       => 6,
                'category'    => 'Librería',
                'image'       => 'prod_6a30d693a01257.08795453.png',
                'is_active'   => 1,
            ],
            [
                'name'        => 'Set de Resaltadores Neón en Estuche',
                'description' => 'Dale color a tus resúmenes y salvá el cuatrimestre. 🖍️ Porque estudiar textos eternos es mucho más fácil cuando separás las ideas principales. Este set premium viene en una caja rígida organizadora e incluye 6 resaltadores de punta biselada en los tonos neón más buscados (amarillo, rosa, azul, verde, naranja y violeta). Cada marcador lleva el sol de la UNNE estampado, combinando diseño y funcionalidad para tus jornadas de lectura.',
                'price'       => 15800,
                'stock'       => 7,
                'category'    => 'Librería',
                'image'       => 'prod_6a30d6aaf3ed60.68412462.png',
                'is_active'   => 1,
            ],
        ];
    }
}
