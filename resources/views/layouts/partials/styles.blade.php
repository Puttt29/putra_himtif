 <script src="https://cdn.tailwindcss.com"></script>
 <script src="https://unpkg.com/heroicons@2.1.5/24/outline/index.js"></script>
 <script>
     tailwind.config = {
         theme: {
             extend: {
                 colors: {
                     base: "#EFEAE6",
                     surface: "#F7F4F1",
                     surfaceSoft: "#ECE5DF",

                     brand: "#D8CFC8",
                     brandDark: "#C5BAB1",

                     accent: "#9C8579",
                     accentHover: "#856F64",

                     textmain: "#3F3A37",
                     textmuted: "#6B6460",
                 },
             },
         },
     };
 </script>

 <style>
     /* Sidebar scroll hidden but usable */
     .scrollbar-hide {
         -ms-overflow-style: none;
         /* IE & Edge */
         scrollbar-width: none;
         /* Firefox */
     }

     .scrollbar-hide::-webkit-scrollbar {
         display: none;
         /* Chrome, Safari */
     }
 </style>
