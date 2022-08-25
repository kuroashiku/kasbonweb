<script type="text/javascript" src="<?= base_url('js/custom/kunobgyn_view.js') ?>"></script>
<script type="text/javascript" src="<?= base_url('js/custom/kunbiayaobg_view.js') ?>"></script>
<link rel="stylesheet" type="text/css" href="<?= base_url('css/dialog.css') ?>">
<div id="kunobgyn-view" class="h-100">
    <div id="dlg-wrapper">
        <div id="dlg-toolbar">
            <div class="title">
                <div class="label-sm">ID</div>
                <div class="dat-col field" id="kog_id">
                    <input id="<?=uniqid('og_')?>" class="text in" readonly=true>         
                </div>
            </div>
            <div id="kog-reset" class="clickbox"></div>
            <div id="kog-save" class="clickbox"></div>
        </div>
        <div id="dlg-body">  
            <div id="dlg-sidebar">
                <div class="dlg-tab-group">
                    <div class="dlg-tab-item" id="tab-kondisi">
                        Kondisi Pasien
                    </div>
                </div>
                <div class="dlg-tab-group">
                    <div class="dlg-tab-label">
                        Data Subyektif
                    </div>
                    <div class="dlg-tab-item" id="tab-masalah">
                        Masalah
                    </div>
                    <div class="dlg-tab-item" id="tab-riwayat">
                        Riwayat
                    </div>
                    <div class="dlg-tab-item" id="tab-kb">
                        Keluarga Berencana
                    </div>
                </div>
                <div class="dlg-tab-group">
                    <div class="dlg-tab-label">
                        Data Obyektif
                    </div>
                    <div class="dlg-tab-item" id="tab-fisik">
                        Fisik
                    </div>
                    <div class="dlg-tab-item" id="tab-nifas">
                        Khusus & Nifas
                    </div>
                    <div class="dlg-tab-item" id="tab-lain">
                        Lain-lain
                    </div>
                </div>
            </div>
            <div id="dlg-content">
                <h2 id="content-kondisi">Kondisi Pasien</h2>
                <div class="dlg-row">
                    <div class="label">Kondisi saat kunjungan</div>
                    <div id="kog_kondisi" class="dat-col field" aria-multiselectable="true">
                        <div class="clickbox">
                            <input id="kog_kondisi_1" name="kog_kondisi" class="radio in" value="Hamil">
                        </div>
                        <div class="clickbox">
                            <input id="kog_kondisi_2" name="kog_kondisi" class="radio in" value="Pasca melahirkan">
                        </div>
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label"></div>
                    <div id="kog_kunulang" class="dat-col field clickbox">
                        <input id="kog_kunulang_1" class="check in" aria-label="Kunjungan ulang">
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Efek samping yang dialami</div>
                    <div id="kog_efeksmp" class="dat-col field">
                        <input id="kog_efeksmp_1" class="text in" placeholder="Efek samping setelah mendapat pengobatan/tindakan sebelumnya" aria-multiline="true" height=80 readonly>
                    </div>
                </div>
                <hr/>
                <h1>Data Subyektif</h1>
                <h2 id="content-masalah">Masalah</h2>
                <div class="dlg-row">
                    <div class="label">Masalah haid</div>
                    <div id="kog_haidproblem" class="dat-col field" aria-multiselectable="true">
                        <div class="clickbox">
                            <input id="<?=uniqid('og_')?>" class="check in" value='Dismenorroe'>
                        </div>
                        <div class="clickbox">
                            <input id="<?=uniqid('og_')?>" class="check in" value="Spoting">
                        </div>
                        <div class="clickbox">
                            <input id="<?=uniqid('og_')?>" class="check in" value="Menorragia">
                        </div>
                        <div class="clickbox">
                            <input id="<?=uniqid('og_')?>" class="check in" value="Metrorhagia">
                        </div>
                        <div class="clickbox">
                            <input id="<?=uniqid('og_')?>" class="check in" value="Syndrom pre menstruasi">
                        </div>
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Masalah kehamilan sekarang</div>
                    <div id="kog_masalah" class="dat-col field" aria-multiselectable="true">
                        <div class="clickbox">
                            <input id="<?=uniqid('og_')?>" class="check in" value="Mual">
                        </div>
                        <div class="clickbox">
                            <input id="<?=uniqid('og_')?>" class="check in" value="Muntah">
                        </div>
                        <div class="clickbox">
                            <input id="<?=uniqid('og_')?>" class="check in" value="Pendarahan">
                        </div>
                        <input id="<?=uniqid('og_')?>" class="text in" placeholder="Lainnya, sebutkan">
                    </div>
                </div>
                <h2 id="content-riwayat">Riwayat</h2>
                <div class="dlg-row">
                    <div class="label">Umur menarrche</div>
                    <div id="kog_menarrche" class="dat-col field">
                        <input id="<?=uniqid('og_')?>" class="number in" min="0" max="100" width="80">
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Haid terakhir</div>
                    <div id="kog_lasthaid" class="dat-col field">
                        <input id="<?=uniqid('og_')?>" class="date in">
                    </div>
                </div>
                <div class="dlg-row last-row">
                    <div class="label">
                        Riwayat perkawinan 
                    </div>
                    <div id="kog_rikawin" class="dat-col field">
                        <input id="<?=uniqid('og_')?>" class="text in" placeholder="1. umur saya/suami: 25/30" aria-multiline="true" height=80>
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Gravida (G)</div>
                    <div id="kog_gravida" class="dat-col field">
                        <input id="<?=uniqid('og_')?>" class="number in" min="0" max="100" width="80">
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Paritas (P)</div>
                    <div id="kog_paritas" class="dat-col field">
                        <input id="<?=uniqid('og_')?>" class="number in" min="0" max="100" width="80">
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Abortus (A)</div>
                    <div id="kog_abortus" class="dat-col field">
                        <input id="<?=uniqid('og_')?>" class="number in" min="0" max="100" width="80">
                    </div>
                </div>
                <div id="obg-rihamil"></div>
                
                <div class="dlg-row">
                    <div class="label">Riwayat opname</div>
                    <div id="kog_rirawat" class="dat-col field">
                        <input id="<?=uniqid('og_')?>" class="text in" placeholder="1. Sakit...., di RS...." aria-multiline="true" height=80>
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Riwayat operasi</div>
                    <div id="kog_rioperasi"  class="dat-col field">
                        <input id="<?=uniqid('og_')?>" class="text in" placeholder="1. Operasi...., di RS...." aria-multiline="true" height=80>
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Riwayat penyakit keluarga (ayah, ibu, saudara, paman, bibi)</div>
                    <div class="dat-col field flex-container" id="kog_rikit_kel" aria-multiselectable="true">
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Kanker">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Penyakit hati">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Hipertensi">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Diabetes">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Ginjal">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="TBC">
                            </div>
                        </div>
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Penyakit jiwa">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Kelainan bawaan">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Hamil kembar">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Epilepsi">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Alergi">
                            </div>
                            <input id="<?=uniqid('og_')?>" class="text in" placeholder="Lainnya, sebutkan">
                        </div>
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Riwayat gynokologi</div>
                    <div class="dat-col field flex-container" id="kog_rigyn" aria-multiselectable="true">
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Infetilitas">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Inveksi virus">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="PMS">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Cervitis kronis">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Endometriosis">
                            </div>
                        </div>
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Myoma">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Plip servix">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Kanker kandungan">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Operasi kandungan">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Perkosaan">
                            </div>
                        </div>
                    </div>
                </div>
                <h2 id="content-kb">Keluarga Berencana</h2>
                <div class="dlg-row">
                    <div class="label">Metode KB yang pernah dipakai</div>
                    <div class="dat-col field" id="kog_kb" >
                        <input id="<?=uniqid('og_')?>" class="text in" placeholder="Jika lebih dari satu pisahkan dengan koma">
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Komplikasi KB</div>
                    <div class="dat-col field" id="kog_kb_pli" aria-multiselectable="true">
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Pendarahan">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="PID/radang panggul">
                            </div>
                        </div>
                        <div class="flex-col col-sm">
                            <input id="<?=uniqid('og_')?>" class="text in" placeholder="Jika lebih dari satu pisahkan dengan koma">
                        </div>
                    </div>
                </div>
                <hr/>
                <h1>Data Obyektif</h1>
                <h2 id="content-fisik">Pemeriksaan Fisik</h2>
                <div class="dlg-row">
                    <div class="label">Mata</div>
                    <div class="dat-col field flex-container" id="kog_mata" aria-multiselectable="true">
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Pandangan Kabur">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Pemandangan dua">
                            </div>
                        </div>
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Selerai cleric">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Conjungtive pucat">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Dada dan aksila</div>
                    <div class="dat-col field flex-container" id="kog_dada" aria-multiselectable="true">
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Mamae symmetric">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Mamae asymmetric">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Puting menonjol">
                            </div>
                        </div>
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Klostrum">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Areola Hiperpegmentasi">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Tumor">
                            </div>
                        </div>  
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Ektramilas</div>
                    <div class="dat-col field flex-container" id="kog_ektramilas" aria-multiselectable="true">
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Tungkai simetris">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Edema +">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Edema -">
                            </div>
                        </div>
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Reflek +">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Reflek -">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Sistem Kardio</div>
                    <div class="dat-col field flex-container" id="kog_kardio" aria-multiselectable="true">
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Dyspnue">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Orthopneu">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Tachypneu">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Wheezing">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Batuk">
                            </div>
                        </div>
                        <div class="flex-col col-sm">
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Sputum">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Batuk darah"> 
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Nyeri dada">
                            </div>
                            <div class="clickbox">
                                <input id="<?=uniqid('og_')?>" class="check in" value="Keringat malam">   
                            </div>
                        </div>                                
                    </div>
                </div>
                <h2 id="content-nifas">Pemeriksaan Khusus dan Nifas</h2>
                <ul>
                    <li>
                        <h5>Obstetrik</h5>
                        <div class="dlg-row">
                            <div class="label">Abdomen</div>
                            <div class="dat-col field flex-container" id="kog_ob_abd" aria-multiselectable="true">
                                <div class="flex-col col-sm">
                                    <div class="clickbox">
                                        <input id="<?=uniqid('og_')?>" class="check in" value="Memanjang">
                                    </div>
                                    <div class="clickbox">
                                        <input id="<?=uniqid('og_')?>" class="check in" value="Melebar">
                                    </div>
                                    <div class="clickbox">
                                        <input id="<?=uniqid('og_')?>" class="check in" value="Pelebaran vena">
                                    </div>
                                    <div class="clickbox">
                                        <input id="<?=uniqid('og_')?>" class="check in" value="Linea alaba">
                                    </div>
                                    <div class="clickbox">
                                        <input id="<?=uniqid('og_')?>" class="check in" value="Linea nigra">
                                    </div>
                                </div>
                                <div class="flex-col col-sm">
                                    <div class="clickbox">
                                        <input id="<?=uniqid('og_')?>" class="check in" value="Striae livide">
                                    </div>
                                    <div class="clickbox">
                                        <input id="<?=uniqid('og_')?>" class="check in" value="Striae albican">
                                    </div>
                                    <div class="clickbox">
                                        <input id="<?=uniqid('og_')?>" class="check in" value="Luka bekas operasi">
                                    </div>
                                    <input id="<?=uniqid('og_')?>" class="text in" placeholder="Lainnya, sebutkan">
                                </div>           
                            </div>
                        </div> 
                        <div class="dlg-row">
                            <div class="label">Palpasi TFU</div>
                            <div class="dat-col field flex-container" id="kog_ob_tfu">
                                <input id="<?=uniqid('og_')?>" class="number in" min="0"> 
                                <span class="suffix">cm</span>   
                            </div>
                        </div> 
                        <div class="dlg-row">
                            <div class="label">Let punggung</div>
                            <div class="dat-col field" id="kog_ob_letpung">
                                <input id="<?=uniqid('og_')?>" class="text in">         
                            </div>
                        </div> 
                        <div class="dlg-row">
                            <div class="label">Prestasi</div>
                            <div class="dat-col field" id="kog_ob_pres">
                                <input id="<?=uniqid('og_')?>" class="text in">         
                            </div>
                        </div> 
                        <div class="dlg-row mb-sm">
                            <div class="label">Aus kultasi</div>
                            <div class="dat-col field flex-container" id="kog_ob_kul">
                                <input id="<?=uniqid('og_')?>" class="number in" min="0"> 
                                <span class="suffix">/menit</span>   
                            </div>
                        </div> 
                        <div class="dlg-row">
                            <div class="label"></div>
                            <div class="dat-col field flex-container" id="kog_ob_kulstat" aria-multiselectable="true">
                                <div class="clickbox">
                                    <input id="kog_ob_kulstat_1" class="radio in" name="kog_ob_kulstat" value="Teratur"> 
                                </div> 
                                <div class="clickbox">
                                    <input id="kog_ob_kulstat_2" class="radio in" name="kog_ob_kulstat" value="Tidak teratur"> 
                                </div> 
                            </div>
                        </div>
                        <div class="dlg-row mb-sm">
                            <div class="label">His/kontraksi</div>
                            <div class="dat-col field flex-container" id="kog_ob_kon">
                                <input id="<?=uniqid('og_')?>" class="number in" min="0"> 
                                <span class="suffix">/menit</span>
                            </div>
                        </div>
                        <div class="dlg-row">
                            <div class="label"></div>
                            <div class="dat-col field flex-container" id="kog_ob_konstat" aria-multiselectable="true">
                                <div class="clickbox">
                                    <input id="kog_ob_konstat_1" name="kog_ob_konstat" class="radio in" value="Teratur"> 
                                </div> 
                                <div class="clickbox">
                                    <input id="kog_ob_konstat_2" name="kog_ob_konstat" class="radio in" value="Tidak teratur"> 
                                </div> 
                            </div>
                        </div>
                    </li>
                    <li>
                        <h5>Gynekologi</h5>
                        <div class="dlg-row">
                            <div class="label">Inspeksi pengeluaran per vulva</div>
                            <div class="dat-col field" id="kog_gy_vul" aria-multiselectable="true">
                                <div class="clickbox">
                                    <input id="<?=uniqid('og_')?>" class="check in" value="Darah">
                                </div>
                                <div class="clickbox">
                                    <input id="<?=uniqid('og_')?>" class="check in" value="Lendir">
                                </div>
                                <div class="clickbox">
                                    <input id="<?=uniqid('og_')?>" class="check in" value="Air ketuban">  
                                </div>   
                            </div>
                        </div> 
                        <div class="dlg-row">
                            <div class="label">Inspekullo vagina</div>
                            <div class="dat-col field" id="kog_gy_vag">
                                <input id="<?=uniqid('og_')?>" class="text in">         
                            </div>
                        </div> 
                        <div class="dlg-row">
                            <div class="label">Vagina toucher</div>
                            <div class="dat-col field" id="kog_gy_touch">
                                <input id="<?=uniqid('og_')?>" class="text in">         
                            </div>
                        </div> 
                        <div class="dlg-row">
                            <div class="label">Kesan panggul</div>
                            <div class="dat-col field" id="kog_gy_pang">
                                <input id="<?=uniqid('og_')?>" class="text in">         
                            </div>
                        </div> 
                        <div class="dlg-row">
                            <div class="label">Imbang feto pelvic</div>
                            <div class="dat-col field" id="kog_gy_feto">
                                <input id="<?=uniqid('og_')?>" class="text in">         
                            </div>
                        </div> 
                    </li>
                    <li>
                        <h5>Nifas</h5>
                        <div class="dlg-row">
                            <div class="label">Fut</div>
                            <div class="dat-col field" id="kog_nf_fut">
                                <input id="<?=uniqid('og_')?>" class="text in">        
                            </div>
                        </div> 
                        <div class="dlg-row">
                            <div class="label">Contraksi ut</div>
                            <div class="dat-col field" id="kog_nf_ctut">
                                <input id="<?=uniqid('og_')?>" class="text in">        
                            </div>
                        </div> 
                        <div class="dlg-row">
                            <div class="label">Lochea</div>
                            <div class="dat-col field" id="kog_nf_loc">
                                <input id="<?=uniqid('og_')?>" class="text in">        
                            </div>
                        </div> 
                        <div class="dlg-row">
                            <div class="label">Luka jalan lahir</div>
                            <div class="dat-col field" id="kog_nf_luk">
                                <input id="<?=uniqid('og_')?>" class="text in">        
                            </div>
                        </div> 
                        
                    </li>
                </ul>
                <h2 id="content-lain">Lain-lain</h2>
                <div class="dlg-row">
                    <div class="label">Keterangan lain</div>
                    <div class="dat-col field" id="kog_ketlain">
                        <input id="<?=uniqid('og_')?>" class="text in" aria-multiline="true" height=80>
                    </div>
                </div>
                <div class="dlg-row">
                    <div class="label">Penata laksanaan</div>
                    <div class="dat-col field" id="kog_pelaksana">
                        <input id="<?=uniqid('og_')?>" class="text in" aria-multiline="true" height=80>
                    </div>
                </div>
            </div>
        </div>
        <div id="dlg-footer">
            <div id="kbio-grid"></div>
            <div id="kbio-grid-tb" class="toolbar">
                <div id="kbio-btn-add"></div>
                <div id="kbio-btn-save"></div>
                <div id="kbio-btn-cancel"></div>
                <div id="kbio-btn-del"></div>
            </div>
        </div>
    </div>
</div>