<table width="100%" height="100%" border="0"
    style="font-size:14px;font-family:sans-serif;padding:10px">
    <tr height="50%">
        <td width="30%" id="bigten-chart" colspan="2">
        </td>
        <td width="30%" id="gender-chart">
        </td>
    </tr>
    <tr height="50%">
        <td width="30%" id="pekerjaan-chart">
        </td>
        <td width="30%" id="layanan-chart">
        </td>
        <td width="30%" id="pembayaran-chart">
        </td>
    </tr>
</table>

<script>
    $(function() {
        var dataBigten;
        if (globalConfig.lok_jenis == 1) dataBigten = [
            ['ISPA',     Math.max(90, Math.floor(Math.random()*200))],
            ['Diare',    Math.max(80, Math.floor(Math.random()*200))],
            ['Flu',      Math.max(20, Math.floor(Math.random()*100))],
            ['Lambung',  Math.max(20, Math.floor(Math.random()*60))],
            ['Jantung',  Math.max(20, Math.floor(Math.random()*60))],
            ['Ginjal',   Math.max(20, Math.floor(Math.random()*30))],
            ['Mata',     Math.max(20, Math.floor(Math.random()*50))],
            ['Gigi',     Math.max(20, Math.floor(Math.random()*70))],
            ['Migrain',  Math.max(20, Math.floor(Math.random()*40))],
            ['Diabetes', Math.max(20, Math.floor(Math.random()*60))]
        ];
        else dataBigten = [
            ['Cacar',         Math.max(20, Math.floor(Math.random()*200))],
            ['Scrapie',       Math.max(20, Math.floor(Math.random()*200))],
            ['Panleukopenia', Math.max(20, Math.floor(Math.random()*100))],
            ['Rabies',        Math.max(50, Math.floor(Math.random()*160))],
            ['Septisemia',    Math.max(20, Math.floor(Math.random()*60))],
            ['Agalaksia',     Math.max(20, Math.floor(Math.random()*30))],
            ['Antraks',       Math.max(40, Math.floor(Math.random()*150))],
            ['Botulisme',     Math.max(20, Math.floor(Math.random()*70))],
            ['Pullorum',      Math.max(20, Math.floor(Math.random()*40))],
            ['Snot',          Math.max(20, Math.floor(Math.random()*60))]
        ];
        $('#bigten-chart').jqplot([dataBigten], {
            title:'Data 10 Besar Penyakit'+
                (globalConfig.lok_jenis == 2?' Hewan':''),
            gridPadding: {
                top:30,
                bottom:30,
                left:30,
                right:0
            },
            grid: {
                shadow:false
            },
            seriesDefaults:{
                shadow:false,
                renderer:$.jqplot.BarRenderer,
                rendererOptions: {
                    showDataLabels:true,
                    varyBarColor:true
                },
                pointLabels: {show:true}
            },
            axes:{
                xaxis:{
                    renderer:$.jqplot.CategoryAxisRenderer
                }
            }
        });

        var dataPekerjaan = [
            ['Freelance',    Math.max(20, Math.floor(Math.random()*100))],
            ['Guru',         Math.max(20, Math.floor(Math.random()*100))],
            ['Wiraswasta',   Math.max(20, Math.floor(Math.random()*100))], 
            ['Petani',       Math.max(20, Math.floor(Math.random()*100))],
            ['TNI',          Math.max(20, Math.floor(Math.random()*100))],
            ['Pengacara',    Math.max(20, Math.floor(Math.random()*100))],
            ['Dokter',       Math.max(20, Math.floor(Math.random()*100))],
            ['Tenaga medis', Math.max(20, Math.floor(Math.random()*100))],
            ['POLRI',        Math.max(20, Math.floor(Math.random()*100))]
        ];
        $('#pekerjaan-chart').jqplot([dataPekerjaan], {
            title:'Data '+(globalConfig.lok_jenis == 2?'Pemilik Hewan':'Pasien')
            +' Berdasarkan Pekerjaan',
            gridPadding: {
                top:30,
                bottom:0,
                left:0,
                right:0
            },
            grid: {
                shadow:false
            },
            seriesDefaults: {
                shadow:false,
                renderer:$.jqplot.PieRenderer,
                rendererOptions: {
                    sliceMargin:2,
                    showDataLabels:true
                }
            }, 
            legend: {
                show:true,
                location:'e'
            }
        });

        var dataGender;
        if (globalConfig.lok_jenis == 1) dataGender = [
            ['Laki-laki', Math.max(20, Math.floor(Math.random()*100))],
            ['Perempuan', Math.max(20, Math.floor(Math.random()*100))]
        ];
        else dataGender = [
            ['Jantan', Math.max(20, Math.floor(Math.random()*100))],
            ['Betina', Math.max(20, Math.floor(Math.random()*100))]
        ];
        $('#gender-chart').jqplot([dataGender], {
            title:globalConfig.lok_jenis == 2
                ?'Data Pasien Hewan Bedasar Kelamin'
                :'Data Pasien Bedasar Gender',
            gridPadding: {
                top:30,
                bottom:30,
                left:50,
                right:0
            },
            grid: {
                shadow:false
            },
            seriesDefaults:{
                shadow:false,
                renderer:$.jqplot.BarRenderer,
                rendererOptions: {
                    showDataLabels:true,
                    varyBarColor:true
                },
                pointLabels: {show:true}
            },
            axes:{
                xaxis:{
                    renderer: $.jqplot.CategoryAxisRenderer
                }
            }
        });

        var dataLayanan;
        if (globalConfig.lok_jenis == 1) dataLayanan = [
            ['Klinik Bedah',     Math.max(20, Math.floor(Math.random()*100))],
            ['Klinik Gigi',      Math.max(20, Math.floor(Math.random()*100))],
            ['Klinik Umum',      Math.max(20, Math.floor(Math.random()*100))], 
            ['Klinik Kandungan', Math.max(20, Math.floor(Math.random()*100))],
            ['Klinik Dalam',     Math.max(20, Math.floor(Math.random()*100))],
            ['Klinik TB DOTS',   Math.max(20, Math.floor(Math.random()*100))],
            ['Klinik Anak',      Math.max(20, Math.floor(Math.random()*100))]
        ];
        else dataLayanan = [
            ['Bedah Mayor',      Math.max(20, Math.floor(Math.random()*100))],
            ['Bedah Minor',      Math.max(20, Math.floor(Math.random()*100))],
            ['Emergency Action', Math.max(20, Math.floor(Math.random()*100))], 
            ['Pet Shop',         Math.max(20, Math.floor(Math.random()*100))],
            ['Salon Hewan',      Math.max(20, Math.floor(Math.random()*100))],
            ['Vaksinasi',        Math.max(20, Math.floor(Math.random()*100))],
            ['Tindakan Medis',   Math.max(20, Math.floor(Math.random()*100))]
        ];

        $('#layanan-chart').jqplot([dataLayanan], {
            title:'Data Penggunaan Layanan',
            gridPadding: {
                top:30,
                bottom:0,
                left:20,
                right:0
            },
            grid: {
                shadow:false
            },
            seriesDefaults: {
                shadow:false,
                renderer:$.jqplot.PieRenderer,
                rendererOptions: {
                    sliceMargin:2,
                    showDataLabels:true
                }
            }, 
            legend: {
                show:true,
                location:'e'
            }
        });

        var dataPembayaran;
        if (globalConfig.lok_jenis == 1) dataPembayaran = [
            ['Umum',           Math.max(20, Math.floor(Math.random()*100))],
            ['BPJS Kesehatan', Math.max(20, Math.floor(Math.random()*100))],
            ['BPJS T.Kerja',   Math.max(20, Math.floor(Math.random()*100))],
            ['Askes',          Math.max(20, Math.floor(Math.random()*100))],
            ['Taspen',         Math.max(20, Math.floor(Math.random()*100))]
        ];
        else dataPembayaran = [
            ['Mandi kutu',  Math.max(20, Math.floor(Math.random()*100))],
            ['Cukur jamur', Math.max(20, Math.floor(Math.random()*100))],
            ['Sterilisasi', Math.max(20, Math.floor(Math.random()*100))],
            ['Cek sperma',  Math.max(20, Math.floor(Math.random()*100))],
            ['Sirkumsisi',  Math.max(20, Math.floor(Math.random()*100))]
        ];
        $('#pembayaran-chart').jqplot([dataPembayaran], {
            title:globalConfig.lok_jenis == 1?'Data Jenis Pembayaran':'Data Tindakan',
            gridPadding: {
                top:30,
                bottom:30,
                left:50,
                right:0
            },
            grid: {
                shadow:false
            },
            seriesDefaults:{
                shadow:false,
                renderer:$.jqplot.BarRenderer,
                rendererOptions: {
                    varyBarColor:true,
                    showDataLabels:true
                },
                pointLabels: {show:true}
            },
            axes:{
                xaxis:{
                    renderer: $.jqplot.CategoryAxisRenderer
                }
            }
        });
    });
</script>