var currentWin = window, main_NGL_module = null, main_pv = null, main_ngl = null, plip_bond_num_dash = 5, plip_bond_radius = .1, plip_higlight_factor = 2, plip_higlight_bond_num_dash = 1, PLIP = PLIP || {};
PLIP.AbstractViewer = null;
var opacity_fadeout_ligand = .3, opacity_fadeout_protein = .3;

function takeSnapshot(a) {
    ".png" == a && (a = "structure.png");
    0 < $("#pv:visible", currentWin.document).length && $("#pv canvas")[0].toBlob(function (b) {
        saveAs(b, a)
    });
    0 < $("#ngl:visible", currentWin.document).length && ngl_stage.makeImage({factor: 1, antialias: !1, trim: !1, transparent: !1}).then(function (b) {
        NGL.download(b, a)
    })
}

function resetStructure() {
    0 < $("#pv:visible", currentWin.document).length && (pv_viewer.show("*ligands"), pv_viewer.hide("*ligands_lines"), pv_viewer.rm("*res"), pv_viewer.autoZoom(), pv_viewer.requestRedraw());
    0 < $("#ngl:visible", currentWin.document).length && ngl_stage.compList[0].autoView(1200)
}

function spin(a) {
    "undefined" !== typeof pv_viewer && pv_viewer.spin(a);
    null != ngl_stage && (a ? ngl_stage.setSpin([0, 1, 0], -.005) : ngl_stage.setSpin(0))
}

function detach(a) {
    if (currentWin == window) {
        if (0 < $("#pv:visible", currentWin.document).length) currentWin = window.open(a.replace("xx", "pv"), "_blank", "resizable=1,status=1,toolbar=1,location=1,height=700,width=700"), main_pv = pv_viewer; else if (0 < $("#ngl:visible", currentWin.document).length) main_ngl = ngl_stage, main_NGL_module = NGL, currentWin = window.open(a.replace("xx", "ngl"), "_blank", "resizable=1,status=1,toolbar=1,location=1,height=700,width=700"); else return;
        $("#left-panel").removeClass("col-sm-8");
        $("#right-panel").hide();
        $(".alignTbl").alignment("resizeToParent")
    }
}

function refreshBondsAndLigandHighlights(a) {
    refreshHighlightLigands(a);
    refreshBonds(a)
}

function refreshBonds(a) {
    void 0 === a ? plip_bonds.refreshAll() : plip_bonds.refresh(a)
}

function refreshHighlightLigands(a) {
    void 0 === a ? all_ligands.redrawAll() : all_ligands.redraw(a)
}

function attachPV() {
    pv_viewer = main_pv;
    attach()
}

function attachNGL() {
    var a = ngl_stage.viewerControls.getOrientation();
    console.log("detachedOrientationMatrix: ");
    console.log(a);
    NGL = main_NGL_module;
    ngl_stage = main_ngl;
    ngl_stage.removeAllComponents();
    showNGL(a);
    attach()
}

function attach() {
    currentWin = window;
    $("#left-panel").addClass("col-sm-8");
    $("#right-panel").show();
    $("#pv").height($("#viewerWrapper").width());
    0 < $("#ngl:visible", currentWin.document).length && ($("#viewerWrapper").height($("#viewerWrapper").width()), ngl_stage.handleResize());
    "undefined" != typeof superposeTemplates && superposeTemplates(!0);
    $(".alignTbl").alignment("resizeToParent");
    refreshBondsAndLigandHighlights()
}

function centerOnLigand(a, b) {
    0 < $("#pv:visible", currentWin.document).length && centerOnLigandPV(a, b);
    0 < $("#ngl:visible", currentWin.document).length && centerOnLigandNGL(a, b)
}

function highlightLigand(a, b, c) {
    $(".hoverResidue").removeClass("hoverResidue");
    for (var d = {}, e = 0; e < b.length; e++) b[e] && "_" != b[e].charAt(0) && (res = b[e].substring(1), chain = b[e].charAt(0), d[chain] || (d[chain] = []), d[chain].push(res));
    for (var f in d) {
        var g = $("#alignTbl" + c + " tr[keyseqchain=" + f + "]").find("span").toArray();
        if (0 == g.length) {
            var h = $(".alignTbl tr[chain=" + f + "]:visible");
            if (0 == h.length) {
                var l = -1;
                $(".alignNameTbl tr[chain]:visible").each(function () {
                    var b = $(this).closest(".alignNameTbl").attr("id").substring(12),
                        a = $(this).attr("seqno");
                    if (!(parseInt(a) < l)) {
                        var c = $(this).text().split("(");
                        c && 1 < c.length && -1 < c[1].indexOf(f) && (h = h.add($("#alignTbl" + b + " tr[seqno=" + a + "]")), l = parseInt(a))
                    }
                })
            }
            g = h.find("span").toArray()
        }
        for (e = 0; e < d[f].length; e++) for (var k = 0; k < g.length; k++) if (g[k].getAttribute("s") == d[f][e]) {
            g[k].className = "hoverResidue";
            break
        }
    }
    0 < $("#pv:visible", currentWin.document).length && highlightLigandPV(d, b, c);
    0 < $("#ngl:visible", currentWin.document).length && highlightLigandNGL(a, b, c)
}

$("body").on("highlightResidue", function (a, b) {
    if ("structure" != b.src) if (null == b.chain && null == b.resno) 0 < $("#pv:visible", currentWin.document).length && pv_viewer.rm(b.struc_id + "_res"), 0 < $("#ngl:visible", currentWin.document).length && ngl_stage.getRepresentationsByName(b.struc_id + ".hover").list[0].setSelection("none"); else {
        if (0 < $("#pv:visible", currentWin.document).length) return highlightResiduesPV(b.chain, b.resno, b.struc_id, b.multiple);
        if (0 < $("#ngl:visible", currentWin.document).length) return highlightResiduesNGL(b.chain,
            b.resno, b.struc_id, b.multiple)
    }
});
$("body").on("centerResRange", function (a, b) {
    0 < $("#pv:visible", currentWin.document).length && centerResRangePV(b.chain, b.min, b.max, b.struc_id, b.isDisulfid);
    0 < $("#ngl:visible", currentWin.document).length && centerResRangeNGL(b.chain, b.min, b.max, b.struc_id, b.isDisulfid)
});

function selectResidues(a, b, c, d) {
    if (null == a && null == b) 0 < $("#pv:visible", currentWin.document).length && pv_viewer.rm(c + "_res"), 0 < $("#ngl:visible", currentWin.document).length && highlightLigandNGL(all_ligands.getVisibleLigandIds(c), plip_residues.getVisibleResiduesResId(c), c); else {
        if (0 < $("#pv:visible", currentWin.document).length) return highlightResiduesPV(a, b, c, d);
        if (0 < $("#ngl:visible", currentWin.document).length) return highlightResiduesNGL(a, b, c, d)
    }
}

function changeRep(a) {
    a || (a = "cartoon");
    0 < $("#pv:visible", currentWin.document).length && (changeRepPV(a), null != pv_viewer && 0 < pv_viewer.all().length && ("undefined" !== typeof detached && detached ? window.opener.updatecols() : updatecols()));
    0 < $("#ngl:visible", currentWin.document).length && (changeRepNGL(a), "undefined" !== typeof detached && detached ? window.opener.updatecols() : updatecols())
}

function updatecols() {
    $(".alignTbl").first().alignment("viewerInitialised")
}

var ngl_stage = null, ngl_picked_atom = !1, ngl_picked_component = !1;

function getNGL() {
    ngl_stage = new NGL.Stage("ngl", {backgroundColor: "white", hoverTimeout: .005, sampleLevel: 2, tooltip: !1});
    var a = (new NGL.Matrix4).makeRotationX(Math.PI / 2);
    a = (new NGL.Quaternion).setFromRotationMatrix(a);
    ngl_stage.viewerControls.rotate(a);
    a = (new NGL.Matrix4).makeRotationY(Math.PI);
    a = (new NGL.Quaternion).setFromRotationMatrix(a);
    ngl_stage.viewerControls.rotate(a);
    ngl_stage.signals.clicked.add(function (b) {
        if (b && (b.atom || b.bond)) {
            var a = b.atom || b.closestBondAtom;
            b.component.autoView(a.resno +
                ":" + a.chainname, 1E3)
        }
    });
    ngl_stage.signals.hovered.add(function (b) {
        if (!ngl_stage.mouseObserver.pressed) if (b && (b.atom || b.bond)) {
            var a = b.atom || b.closestBondAtom;
            if (!ngl_picked_atom || a.structure.name != ngl_picked_atom.structure.name || a.index != ngl_picked_atom.index) {
                var d = b.component;
                d = getNGLRepresentationByName(d.name, d.name);
                ngl_picked_atom = a;
                ngl_picked_component = b.component;
                var e = a.resname + " " + a.resno + a.inscode;
                "_" != a.chainname && (e += " " + a.chainname);
                "_" != a.chainname && ["cartoon", "tube", "rope"].includes(d.repr.type) &&
                "CA" == a.atomname || (e += " " + a.atomname);
                $("#ngl_status").text(e);
                $("body").trigger("highlightResidue", {src: "structure", struc_id: b.component.name, chain: a.chainname, resno: a.resno});
                "undefined" !== typeof detached && detached ? (window.opener.ngl_picked_atom = ngl_picked_atom, window.opener.updatecols()) : updatecols()
            }
        } else ngl_picked_atom && (ngl_picked_atom = !1, $("#ngl_status").text(""), "undefined" !== typeof detached && detached ? (window.opener.ngl_picked_atom = ngl_picked_atom, window.opener.updatecols()) : updatecols())
    });
    return ngl_stage
}

function getNGLComponentByName(a) {
    return ngl_stage.getComponentsByName(a).list[0]
}

function getNGLRepresentationByName(a, b) {
    return ngl_stage.getRepresentationsByName(b).list[0]
}

function removeNGLComponentByName(a) {
    a = getNGLComponentByName(a);
    ngl_stage.removeComponent(a)
}

function highlightResiduesNGL(a, b, c, d) {
    var e = "";
    if (b) {
        var f = getNGLRepresentationByName(c, c + ".hover");
        d && (e = f.repr.selection.string + " ", e.startsWith("none") && (e = e.substring(5)));
        d = "";
        for (var g = 0; g < a.length; g++) if (b.length) for (var h = 0; h < b.length; h++) b[h][0] == b[h][1] ? isResidueInStructureNGL(c, a[g], b[h][0]) && (d += b[h][0] + ":" + a[g] + " ") : (console.warn("Not checking if the selection is part of the structure"), d += b[h][0] + "-" + b[h][1] + ":" + a[g] + " "); else isResidueInStructureNGL(c, a[g], b) && (d += b + ":" + a[g] + " ");
        new_selection = e + d;
        "" == new_selection && (new_selection = "none");
        f.setSelection(new_selection);
        return new_selection
    }
    c = getNGLComponentByName(c);
    d = "";
    if (1 == a.length) d = (b ? b : "") + ":" + a; else for (g = 0; g < a.length; g++) d += (b ? b : "") + ":" + a[g] + " ";
    c.autoView(d, 1E3);
    return d
}

function centerResRangeNGL(a, b, c, d, e) {
    for (var f = "", g = 0; g < a.length; g++) for (var h = b; h <= c; h++) isResidueInStructureNGL(d, a[g], h) && (f = h == c ? f + (h + ":" + a[g] + " ") : e ? f + (h + ":" + a[g] + " " + c + ":" + a[g] + " ") : f + (h + "-" + c + ":" + a[g] + " "));
    "" != f && getNGLComponentByName(d).autoView(f, 1E3)
}

function centerOnLigandNGL(a, b) {
    for (var c = a[0] + ":_", d = 1; d < a.length; d++) c += " " + a[d] + ":_";
    for (var e in ngl_stage.compList) if (ngl_stage.compList[e].name == b) {
        ngl_stage.compList[e].autoView(c, 1E3);
        break
    }
}

function contactListToNGLSelection(a, b, c) {
    var d = {}, e = null;
    if (void 0 === c || null == c) c = [];
    if (b && 0 < b.length) for (var f = 0; f < b.length; f++) if (b[f]) {
        e = b[f].charAt(0);
        var g = parseInt(b[f].substring(1));
        "_" == e && -1 == c.indexOf(g) && c.push(g);
        isResidueInStructureNGL(a, e, g) && (d[e] || (d[e] = []), d[e][g] = 1)
    }
    b = a = "";
    for (e in d) {
        a += " ( (";
        b += " ( (";
        g = !0;
        for (f = 0; f < d[e].length; f++) if (d[e][f]) {
            g ? g = !1 : (a += " or ", b += " or ");
            a += f;
            b += f - 2;
            start = f;
            do f++; while (d[e][f]);
            1 < f - start && (a += "-" + (f - 1));
            b += "-" + (f + 1)
        }
        a += ") AND :" + e + ") ";
        b +=
            ") AND :" + e + ") "
    }
    return [a, b, c]
}

function isResidueInStructureNGL(a, b, c) {
    var d = {};
    comp = getNGLComponentByName(a);
    try {
        comp.structure.eachChain(function (a) {
            a.chainname == b && (first_residue = a.residueStore.resno[a.residueOffset], last_residue = a.residueStore.resno[a.residueEnd], a.eachResidue(function (a) {
                if (a.resno == c) throw d;
            }))
        })
    } catch (e) {
        if (e === d) return !0
    }
    return !1
}

function highlightLigandNGL(a, b, c, d, e) {
    removeNGLComponentByName(c + "ligandBondsHL");
    void 0 === d && (d = extra_residues_selection);
    void 0 === e && (e = extra_ranges_selection);
    residue_contacts = b.concat(d);
    ranges_contacts = residue_contacts.concat(e);
    var f = contactListToNGLSelection(c, residue_contacts, a);
    a = f[2];
    b = contactListToNGLSelection(c, ranges_contacts);
    f = f[0];
    b = b[1];
    "" == f && (f = "none");
    "" == b ? (b = "none", fadeOutRepresentationNGL(c, c, 1)) : fadeOutRepresentationNGL(c, c, opacity_fadeout_protein);
    for (var g in ngl_stage.compList) if (ngl_stage.compList[g].name ==
        c) {
        for (var h in ngl_stage.compList[g].reprList) ngl_stage.compList[g].reprList[h].name == c + ".residuesHighlight" && ngl_stage.compList[g].reprList[h].setSelection(f), ngl_stage.compList[g].reprList[h].name == c + ".highlight" && (protein_residue_sele_string = "protein AND (" + b + ")", ngl_stage.compList[g].reprList[h].setSelection(protein_residue_sele_string));
        break
    }
    a && 0 < a.length ? (a = ":_ and (" + a.join(" or ") + ")", fadeOutRepresentationNGL(c, c + ".ligands", opacity_fadeout_ligand)) : 0 < d.length || 0 < e.length ? (a = "none", fadeOutRepresentationNGL(c,
        c + ".ligands", opacity_fadeout_ligand)) : (a = "none", fadeOutRepresentationNGL(c, c + ".ligands", 1));
    getNGLRepresentationByName(c, c + ".ligandsHighlight").setSelection(a)
}

function fadeOutRepresentationNGL(a, b, c) {
    repr = getNGLRepresentationByName(a, b, a);
    repr.setParameters({opacity: c})
}

function changeRepNGL(a) {
    $("#repBtnLbl").text(a.charAt(0).toUpperCase() + a.substring(1));
    jQuery.cookie("defaultRepr", a, {expires: 365, path: "/"});
    switch (a) {
        case "lines":
            a = "line";
            break;
        case "ballAndStick":
            a = "ball+stick";
            break;
        case "outline":
            return
    }
    var b = null, c = null;
    ngl_stage.eachRepresentation(function (d) {
        if (!d.name.match(/(ligands|dna|over|buffer)/)) for (i in b = d.name.split(".")[0], ngl_stage.compList) if (comp = ngl_stage.compList[i], comp.name == b) {
            null === c && (c = d.repr.type);
            if ("surface" == c && "surface" != a) {
                console.debug("Adding residuesHighlight representation");
                comp.addRepresentation(a, {name: b + ".residuesHighlight", sele: "none", smoothSheet: !0, roughness: 1, surfaceType: "sas", probeRadius: 1.4, side: "front", opacity: d.getParameters().opacity, scaleFactor: Math.min(1.5, Math.max(.1, 5E4 / comp.structure.atomCount))});
                try {
                    refreshBondsAndLigandHighlights()
                } catch (e) {
                }
            }
            comp.removeRepresentation(d);
            "surface" == a && d.name == b + ".residuesHighlight" ? console.debug("Removing surface residuesHighlight") : comp.addRepresentation(a, {
                name: d.name, sele: d.getParameters().sele, smoothSheet: !0, surfaceType: "sas",
                probeRadius: 1.4, scaleFactor: Math.min(1.5, Math.max(.1, 5E4 / comp.structure.atomCount)), roughness: 1, side: "front", opacity: d.getParameters().opacity
            })
        }
    })
}

function setupNGLRepresentations(a, b, c, d, e) {
    void 0 === c && (c = !1);
    var f = $.cookie("defaultRepr");
    null == f ? f = "cartoon" : "ballAndStick" == f ? f = "ball+stick" : "lines" == f && (f = "line");
    a.setName(b);
    a.addRepresentation(f, {name: b, sele: "not :_ and not nucleic and not :-", smoothSheet: !0, roughness: 1, surfaceType: "sas", probeRadius: 1.4, scaleFactor: Math.min(1.5, Math.max(.1, 5E4 / a.structure.atomCount)), side: "front"});
    a.addRepresentation("ball+stick", {name: b + ".ligands", colorScheme: "element", sele: ":_", side: "front"});
    a.addRepresentation("ball+stick",
        {name: b + ".residuesHighlight", colorScheme: "element", sele: "none", aspectRatio: 1});
    a.addRepresentation("ball+stick", {name: b + ".hover", sele: "none"});
    c && (a.addRepresentation("ball+stick", {name: b + ".ligandsHighlight", colorScheme: "element", sele: "none"}), "surface" != f && a.addRepresentation(f, {
        name: b + ".highlight",
        sele: "none",
        smoothSheet: !0,
        roughness: 1,
        surfaceType: "sas",
        probeRadius: 1.4,
        scaleFactor: Math.min(1.5, Math.max(.1, 5E4 / a.structure.atomCount)),
        side: "front"
    }));
    d.forEach(function (c) {
        scheme = "line" == c ? "element" :
            "base" == c ? "resname" : "moleculetype";
        a.addRepresentation(c, {name: b + ".dna", colorScheme: scheme, sele: "nucleic and not :_"})
    });
    e ? ngl_stage.viewerControls.orient(e) : "function" == typeof on_viewer_ready_function ? on_viewer_ready_function(b, a) : a.autoView(1E3);
    updatecols();
    c && refreshBondsAndLigandHighlights()
}

function setColorForAtom(a, b, c) {
    var d = a.structure().createEmptyView();
    d.addAtom(b);
    a.colorBy(pv.color.uniform(c), d)
}

var prevPicked = null;
document.getElementById("pv") && document.getElementById("pv").addEventListener("mousemove", function (a) {
    var b = pv_viewer.boundingClientRect();
    a = pv_viewer.pick({x: a.clientX - b.left, y: a.clientY - b.top});
    if (null === prevPicked || null === a || a.target() !== prevPicked.atom) {
        null !== prevPicked && setColorForAtom(prevPicked.node, prevPicked.atom, prevPicked.color);
        if (null !== a) {
            b = a.target();
            var c = b.qualifiedName().split(".");
            if ("_" !== c[0].charAt(0) && b.residue()._isAminoacid) {
                var d = [0, 0, 0, 0];
                a.node().getColorForAtom(b, d);
                prevPicked = {atom: b, color: d, node: a.node()};
                setColorForAtom(a.node(), b, "red");
                $("body").trigger("highlightResidue", {src: "structure", struc_id: a.object().geom.name(), chain: c[0], resno: b.residue().num()});
                c = b.residue().name() + " " + b.residue().num() + " " + c[0]
            } else c = b.residue().name() + " " + b.residue().num();
            document.getElementById("pv_status").innerHTML = c
        } else document.getElementById("pv_status").innerHTML = "", prevPicked = null;
        pv_viewer.requestRedraw()
    }
});

function highlightResiduesPV(a, b, c, d) {
    d || pv_viewer.rm(c + "_res");
    d = pv_viewer.get(c);
    if (null != d) {
        d = d.structure();
        if (b) {
            var e = null;
            e = b.length ? d.select({cnames: a.split("")}).residueSelect(function (a) {
                for (var c = 0; c < b.length; c++) if (a.num() >= b[c][0] && a.num() <= b[c][1]) return !0;
                return !1
            }) : d.select({cnames: a.split("")}).residueSelect(function (a) {
                return a.num() == b
            });
            pv_viewer.ballsAndSticks((c ? c + "_" : "") + "res", e);
            return e
        }
        pv_viewer.fitTo(d.select({cnames: a.split("")}));
        pv_viewer.requestRedraw()
    }
}

function centerResRangePV(a, b, c, d, e) {
    d = pv_viewer.get(d);
    if (null != d) {
        var f = null;
        f = 1 == a.length ? d.structure().residueSelect(function (d) {
            return e ? (d.num() == b || d.num() == c) && d.chain().name() == a : d.num() >= b && d.num() <= c && d.chain().name() == a
        }) : d.structure().residueSelect(function (d) {
            return e ? (d.num() == b || d.num() == c) && -1 < a.indexOf(d.chain().name()) : d.num() >= b && d.num() <= c && -1 < a.indexOf(d.chain().name())
        });
        pv_viewer.fitTo(f);
        pv_viewer.requestRedraw()
    }
}

function centerOnLigandPV(a, b) {
    var c = pv_viewer.get(b + ".ligands");
    null != c && (c = c.structure(), c = c.residueSelect(function (b) {
        for (var c = 0; c < a.length; c++) if (b.num() == a[c]) return !0
    }), pv_viewer.fitTo(c), pv_viewer.requestRedraw())
}

function highlightLigandPV(a, b, c) {
    pv_viewer.rm("*ligandContactsHL");
    null != pv_viewer.get(c + ".ligands") && a && (b = pv_viewer.get(c).structure().residueSelect(function (b) {
        var c = b.chain().name();
        b = b.num();
        if (c = a[c]) for (var d = 0; d < c.length; d++) if (b == c[d]) return !0
    }), pv_viewer.lines(c + "ligandContactsHL", b), pv_viewer.requestRedraw())
}

function changeRepPV(a) {
    a.match(/(cartoon|tube|trace|lines|outline|fog)/) || (a = "cartoon");
    "outline" == a ? (jQuery.cookie("pv_outline", !pv_viewer.options("outline"), {expires: 365, path: "/"}), pv_viewer.options("outline", !pv_viewer.options("outline"))) : "fog" == a ? (jQuery.cookie("pv_fog", !pv_viewer.options("fog"), {
        expires: 365,
        path: "/"
    }), pv_viewer.options("fog", !pv_viewer.options("fog"))) : a.match(/(cartoon|tube|trace|lines|ballAndStick)/) && ($("#repBtnLbl").text(a.charAt(0).toUpperCase() + a.substring(1)), jQuery.cookie("defaultRepr",
        a, {expires: 365, path: "/"}), pv_viewer.forEach(function (b) {
        var c = b.name();
        if (!c.match(/(ligand|dna|res|LigandBond)/)) switch (b = b.structure(), pv_viewer.rm(c), a) {
            case "cartoon":
                pv_viewer.cartoon(c, b, {color: pv.color.uniform("white")});
                break;
            case "tube":
                pv_viewer.tube(c, b, {color: pv.color.uniform("white")});
                break;
            case "trace":
                pv_viewer.trace(c, b, {color: pv.color.uniform("white")});
                break;
            case "lines":
                pv_viewer.lines(c, b, {color: pv.color.uniform("white")});
                break;
            case "ballAndstick":
                pv_viewer.ballsAndSticks(c,
                    b, {color: pv.color.uniform("white")})
        }
    }))
}

function midpoint1d(a, b, c) {
    return a + (b - a) * c
}

function midpoint3d(a, b, c) {
    return [midpoint1d(a[0], b[0], c), midpoint1d(a[1], b[1], c), midpoint1d(a[2], b[2], c)]
}

function showWaterPV() {
    pv_viewer.ballsAndSticks("water", pv_viewer.get("template").structure().select({rnames: ["HOH"]}))
}

function showWaterNGL() {
    log.console("showWaterNGL not implemented")
}

function showWater() {
    0 < $("#pv:visible", currentWin.document).length && showWaterPV();
    0 < $("#ngl:visible", currentWin.document).length && showWaterNGL()
}

function toggleBond(a, b) {
    PLIP.AbstractViewer.isStructureEnabled(a) && (plip_bonds.getBondById(a, b).toggle(), deHighlightBond(a, b, !0))
}

function highlightBond(a, b) {
    PLIP.AbstractViewer.isStructureEnabled(a) && plip_bonds.getBondById(a, b).highlight()
}

function deHighlightBond(a, b, c) {
    PLIP.AbstractViewer.isStructureEnabled(a) && (null == c && (c = !0), plip_bonds.getBondById(a, b).dehighlight(!0, c))
}

function highlightBonds(a, b) {
    PLIP.AbstractViewer.isStructureEnabled(a) && (plip_bonds.getBondsById(a, b).forEach(function (a) {
        a.highlight(!1)
    }), plip_residues.redraw(a))
}

function deHighlightBonds(a, b) {
    PLIP.AbstractViewer.isStructureEnabled(a) && (plip_bonds.getBondsById(a, b).forEach(function (a) {
        a.dehighlight(!1)
    }), plip_residues.redraw(a))
}

function clickBonds(a, b, c) {
    toggleBonds(a, b, c);
    $("#plip_bonds_" + c).prop("checked") && fitToBonds(a, b, c)
}

function toggleBonds(a, b, c) {
    a = $("#plip_bonds_" + c);
    b = a.prop("checked");
    a.prop("checked", !b);
    a.trigger("change")
}

function changeBondsCheckbox(a, b, c) {
    var d = $("#plip_bonds_" + c), e = d.prop("checked");
    PLIP.AbstractViewer.isStructureEnabled(a) ? (plip_bonds.getBondsById(a, b).forEach(function (a) {
        e ? a.show(!1) : a.hide(!1);
        a.dehighlight(!1, !0)
    }), plip_residues.redraw(a), e && plip_interactions_fit_bonds && fitToBonds(a, b, c)) : d.prop("checked", !1)
}

function showBonds(a, b) {
    PLIP.AbstractViewer.isStructureEnabled(a) && (plip_bonds.getBondsById(a, b).forEach(function (a) {
        a.show(!1);
        a.dehighlight(!1, !0)
    }), plip_residues.redraw(a))
}

function hideBonds(a, b) {
    PLIP.AbstractViewer.isStructureEnabled(a) && (plip_bonds.getBondsById(a, b).forEach(function (a) {
        a.hide(!1);
        a.dehighlight(!1, !0)
    }), plip_residues.redraw(a))
}

function fitToBond(a, b) {
    PLIP.AbstractViewer.isStructureEnabled(a) && plip_bonds.getBondById(a, b).fitView()
}

function fitToResidue(a, b, c) {
    PLIP.AbstractViewer.isStructureEnabled(a) && (residue = plip_residues.getResidue(a, b), c ? (ligand = all_ligands.getLigandById(a, c), PLIP.AbstractViewer.fitView(a, [ligand], [residue])) : PLIP.AbstractViewer.fitView(a, null, [residue]))
}

function fitToBonds(a, b) {
    PLIP.AbstractViewer.isStructureEnabled(a) && plip_bonds.fitViewBonds(a, b)
}

function showResidues(a, b, c) {
    PLIP.AbstractViewer.isStructureEnabled(a) && (plip_residues.getResiduesByResId(a, b).forEach(function (a) {
        a.show(!1)
    }), plip_residues.redraw(a))
}

function hideResidues(a, b, c) {
    PLIP.AbstractViewer.isStructureEnabled(a) && (plip_residues.getResiduesByResId(a, b).forEach(function (a) {
        a.hide(!1)
    }), plip_residues.redraw(a))
}

function showLigands(a, b) {
    PLIP.AbstractViewer.isStructureEnabled(a) && (all_ligands.getLigandsById(a, b).forEach(function (a) {
        a.show(!1, !0)
    }), all_ligands.redraw(a))
}

function hideLigands(a, b) {
    PLIP.AbstractViewer.isStructureEnabled(a) && (all_ligands.getLigandsById(a, b).forEach(function (a) {
        a.hide(!1, null, !0)
    }), all_ligands.redraw(a))
}

PLIP.Bonds = function () {
    var a = {};
    this.hasStruc = function (b) {
        return b in a
    };
    this.removeStruc = function (b) {
        b in a && delete a[b]
    };
    this.addStruc = function (b) {
        this.hasStruc(b) || (a[b] = [])
    };
    this.listStruc = function () {
        return Object.keys(a)
    };
    this.getBondById = function (b, c) {
        if (b in a) return a[b].find(function (a) {
            return a.id === c
        });
        console.error("getBondById: no strucId " + b);
        console.trace()
    };
    this.getBondsById = function (b, c) {
        if (b in a) return a[b].filter(function (a) {
            return c.includes(a.id)
        });
        console.error("getBondsById: no strucId " +
            b)
    };
    this.add = function (b) {
        this.addStruc(b.strucId);
        a[b.strucId].push(b)
    };
    this.showAll = function (b) {
        for (var c = a[b], d = 0; d < c.length; d++) c[d].isVisible() || c[d].show(!1);
        plip_residues.redraw(b)
    };
    this.hideAll = function (b) {
        for (var c = a[b], d = 0; d < c.length; d++) c[d].isVisible() && c[d].hide(!1);
        plip_residues.redraw(b)
    };
    this.fitViewBonds = function (a, c) {
        var b = this.getBondsById(a, c), e = b.map(function (a) {
            return a.ligand
        }).filter(function (a, b, c) {
            return c.indexOf(a) == b
        });
        b = b.map(function (a) {
            return a.residue
        }).filter(function (a,
                            b, c) {
            return c.indexOf(a) == b
        });
        PLIP.AbstractViewer.fitView(a, e, b)
    };
    this.refresh = function (b) {
        for (var c in a[b]) a[b][c].refresh()
    };
    this.refreshAll = function () {
        for (var b in a) PLIP.AbstractViewer.isStructureEnabled(b) && this.refresh(b)
    }
};
PLIP.Bond = function (a, b, c, d, e, f, g, h, l) {
    var k = !1, p = !1, m = void 0, q = "LigandBond" + a, n = void 0, r = "LigandBondHighlight" + a;
    this.show = function (a) {
        null == a && (a = !0);
        this.isVisible() ? console.info("Bond already visible") : PLIP.AbstractViewer.isStructureEnabled(this.strucId) && (this.draw(), void 0 !== this.residue && this.residue.show(!1), this.ligand.show(a), k = !0, a && pv_viewer.requestRedraw())
    };
    this.hide = function (a) {
        null == a && (a = !0);
        PLIP.AbstractViewer.isStructureEnabled(this.strucId) && this.isVisible() && (this.destroy(), k =
            !1, void 0 !== this.residue && this.residue.hide(!1), this.ligand.hide(a), a && pv_viewer.requestRedraw())
    };
    this.draw = function () {
        null == this.coo3 ? PLIP.AbstractViewer.addBondLine(this.getBondMesh(), this.coo1, this.coo2, plip_bond_num_dash, plip_bond_radius, this.color, !0) : (PLIP.AbstractViewer.addBondLine(this.getBondMesh(), this.coo1, this.coo3, plip_bond_num_dash, plip_bond_radius, this.color, !1), PLIP.AbstractViewer.addBondLine(this.getBondMesh(), this.coo2, this.coo3, plip_bond_num_dash, plip_bond_radius, this.color, !1),
            PLIP.AbstractViewer.addWaterCircle(this.getBondMesh(), this.coo3, 2 * plip_bond_radius, this.color, !0))
    };
    this.destroy = function () {
        m = PLIP.AbstractViewer.removeMesh(q)
    };
    this.refresh = function () {
        this.destroy();
        this.isVisible() && this.draw();
        this.destroyHighlight();
        this.isHighlighted() && this.drawHighlight()
    };
    this.toggle = function () {
        this.isVisible() ? this.hide() : this.show()
    };
    this.isVisible = function () {
        return k
    };
    this.getBondMesh = function () {
        void 0 == m && (m = PLIP.AbstractViewer.newBondMesh(q));
        return m
    };
    this.highlight =
        function (a) {
            null == a && (a = !0);
            PLIP.AbstractViewer.isStructureEnabled(this.strucId) && !this.isHighlighted() && (this.drawHighlight(), void 0 !== this.residue && this.residue.show(!1), this.ligand.show(a), p = !0)
        };
    this.drawHighlight = function () {
        null == this.coo3 ? PLIP.AbstractViewer.addBondLine(this.getBondMeshHighlight(), this.coo1, this.coo2, plip_higlight_bond_num_dash, plip_bond_radius * plip_higlight_factor, this.color, !0) : (PLIP.AbstractViewer.addBondLine(this.getBondMeshHighlight(), this.coo1, this.coo3, plip_higlight_bond_num_dash,
            plip_bond_radius * plip_higlight_factor, this.color, !1), PLIP.AbstractViewer.addBondLine(this.getBondMeshHighlight(), this.coo2, this.coo3, plip_higlight_bond_num_dash, plip_bond_radius * plip_higlight_factor, this.color, !1), PLIP.AbstractViewer.addWaterCircle(this.getBondMeshHighlight(), this.coo3, plip_bond_radius * plip_higlight_factor * 2, this.color, !0))
    };
    this.destroyHighlight = function () {
        n = PLIP.AbstractViewer.removeMesh(r)
    };
    this.dehighlight = function (a) {
        null == a && (a = !0);
        PLIP.AbstractViewer.isStructureEnabled(this.strucId) &&
        this.isHighlighted() && this.isHighlighted() && (this.destroyHighlight(), void 0 !== this.residue && this.residue.hide(!1), this.ligand.hide(a), p = !1, a && pv_viewer.requestRedraw())
    };
    this.toggleHighlight = function () {
        this.isHighlighted() ? this.dehighlight() : this.highlight()
    };
    this.isHighlighted = function () {
        return p
    };
    this.getBondMeshHighlight = function () {
        void 0 == n && (n = PLIP.AbstractViewer.newBondMesh(r));
        return n
    };
    this.fitView = function () {
        PLIP.AbstractViewer.fitView(this.strucId, [this.ligand], [this.residue])
    };
    this.getLigand =
        function () {
            return this.ligand
        };
    this.residue = plip_residues.getResidue(b, PLIP.makeResidueId(h[0], h[1], h[2]));
    this.id = a;
    this.strucId = b;
    this.ligand = c;
    this.coo1 = d;
    this.coo2 = e;
    this.coo3 = f;
    this.color = g;
    l && this.show()
};
PLIP.makeResidueId = function (a, b, c) {
    return a + ":" + b + c
};
PLIP.Residue = function (a, b, c, d) {
    var e = 0;
    this.show = function (a) {
        null == a && (a = !0);
        PLIP.AbstractViewer.isStructureEnabled(this.strucId) && (e++, 0 < e && a && plip_residues.redraw(this.strucId))
    };
    this.hide = function (a, b) {
        null == a && (a = !0);
        null == b && (b = !0);
        PLIP.AbstractViewer.isStructureEnabled(this.strucId) && (b ? e = Math.max(e - 1, 0) : alert("Did not decrement counter! " + this.id + ": " + e), 0 == e && a && plip_residues.redraw(this.strucId))
    };
    this.isVisible = function () {
        return 0 < e
    };
    this.getResId = function () {
        return this.chain + this.position
    };
    this.strucId = a;
    this.id = PLIP.makeResidueId(b, c, d);
    this.chain = b;
    this.one_letter = c;
    this.position = d
};
PLIP.Residues = function () {
    var a = {};
    this.hasStruc = function (b) {
        return b in a
    };
    this.removeStruc = function (b) {
        b in a && delete a[b]
    };
    this.addStruc = function (b) {
        this.hasStruc(b) || (a[b] = [])
    };
    this.listStruc = function () {
        return Object.keys(a)
    };
    this.getStruc = function (b) {
        if (b in a) return a[b];
        void 0 === b ? console.error("StrucId is undefined") : console.error("No such strucId: '" + b + "'");
        console.trace()
    };
    this.addResidue = function (a, c, d, e) {
        this.addStruc(a);
        var b = PLIP.makeResidueId(c, d, e);
        b = this.getResidue(a, b);
        void 0 === b &&
        (b = new PLIP.Residue(a, c, d, e), this.getStruc(a).push(b));
        return b
    };
    this.getResidue = function (b, c) {
        if (b in a) return this.getStruc(b).find(function (a) {
            return a.id == c
        })
    };
    this.getResiduesByResId = function (b, c) {
        if (b in a) return a[b].filter(function (a) {
            return c.includes(a.id)
        })
    };
    this.getVisibleResidues = function (a) {
        return this.getStruc(a).filter(function (a) {
            return a.isVisible()
        })
    };
    this.getVisibleResiduesResId = function (a) {
        return this.getVisibleResidues(a).map(function (a) {
            return a.getResId()
        })
    };
    this.redraw = function (a) {
        highlightLigand(all_ligands.getVisibleLigandIds(a),
            this.getVisibleResiduesResId(a), a);
        pv_viewer.requestRedraw()
    };
    this.redrawAll = function () {
        for (var b in a) PLIP.AbstractViewer.isStructureEnabled(b) && this.redraw(b)
    }
};
PLIP.Ligands = function () {
    var a = {};
    this.hasStruc = function (b) {
        return b in a
    };
    this.removeStruc = function (b) {
        this.hasStruc(b) && delete a[b]
    };
    this.addStruc = function (b) {
        this.hasStruc(b) || (a[b] = [])
    };
    this.listStruc = function () {
        return Object.keys(a)
    };
    this.getStruc = function (b) {
        if (this.hasStruc(b)) return a[b];
        void 0 === b ? console.error("StrucId " + b + " is undefined") : console.error("No such strucId: '" + b + "'");
        console.trace()
    };
    this.getLigandById = function (b, c) {
        if (this.hasStruc(b)) return a[b].find(function (a) {
            return a.id ===
                c
        });
        console.error("getLigandById: no strucId " + b);
        console.trace()
    };
    this.getLigandsById = function (b, c) {
        if (this.hasStruc(b)) return a[b].filter(function (a) {
            return c.includes(a.id)
        });
        console.error("getLigandsById: no strucId " + b)
    };
    this.addLigand = function (a, c) {
        this.addStruc(a);
        var b = this.getLigandById(a, c);
        void 0 === b && (b = new PLIP.Ligand(a, c), this.getStruc(a).push(b));
        return b
    };
    this.getVisibleLigands = function (a) {
        return this.getStruc(a).filter(function (a) {
            return a.isVisible()
        })
    };
    this.getVisibleLigandIds =
        function (a) {
            return this.getVisibleLigands(a).map(function (a) {
                return a.getLigandId()
            })
        };
    this.redraw = function (a) {
        highlightLigand(this.getVisibleLigandIds(a), plip_residues.getVisibleResiduesResId(a), a);
        pv_viewer.requestRedraw()
    };
    this.redrawAll = function () {
        for (var b in a) PLIP.AbstractViewer.isStructureEnabled(b) && this.redraw(b)
    }
};
PLIP.Ligand = function (a, b) {
    var c = 0, d = [], e = [];
    this.show = function (a, b) {
        null == a && (a = !0);
        null == b && (b = !1);
        PLIP.AbstractViewer.isStructureEnabled(this.strucId) && (c++, b && d.forEach(function (a) {
            a.show(!1)
        }), 0 < c && a && all_ligands.redraw(this.strucId))
    };
    this.hide = function (a, b, e) {
        null == a && (a = !0);
        null == e && (e = !1);
        null == b && (b = !0);
        PLIP.AbstractViewer.isStructureEnabled(this.strucId) && (b ? c = Math.max(c - 1, 0) : alert("Did not decrement counter! " + this.id + ": " + c), e && d.forEach(function (a) {
            a.hide(!1)
        }), 0 == c && a && all_ligands.redraw(this.strucId))
    };
    this.addResidue = function (a) {
        -1 == d.indexOf(a) && d.push(a)
    };
    this.addBond = function (a) {
        -1 == e.indexOf(a) && e.push(a)
    };
    this.isVisible = function () {
        return 0 < c
    };
    this.getLigandId = function () {
        return this.id
    };
    this.getResidues = function () {
        return d
    };
    this.strucId = a;
    this.position = this.id = b
};
PLIP.AbstractViewerPV = {
    newBondMesh: function (a) {
        return pv_viewer.customMesh(a)
    }, addWaterCircle: function (a, b, c, d, e) {
        a.addSphere(b, c, {cap: !0, color: d})
    }, addBondLine: function (a, b, c, d, e, f, g) {
        for (g = 1; g <= d; g++) a.addTube(midpoint3d(b, c, (2 * g - 2) / (2 * d - 1)), midpoint3d(b, c, (2 * g - 1) / (2 * d - 1)), e, {cap: !0, color: f})
    }, removeMesh: function (a) {
        pv_viewer.rm(a)
    }, fitView: function (a, b, c) {
        if (this.isStructureEnabled(a)) {
            var d = c.map(function (a) {
                return void 0 === a ? null : a.chain
            }), e = c.map(function (a) {
                return void 0 === a ? null : a.position
            });
            d.length != e.length && console.error("queryResidue -Chains and -Positions array lengths don't match: " + d.length + " != " + e.length);
            c = pv_viewer.get(a).structure().residueSelect(function (a) {
                var c = a.chain().name();
                if ("_" === c) {
                    if (!b) return !1;
                    ligand_positions = b.map(function (a) {
                        return a.position
                    });
                    if (ligand_positions.includes(a.num())) return !0
                }
                for (var f = 0; f < d.length; f++) if (d[f] === c && e[f] === a.num()) return !0;
                return !1
            });
            pv_viewer.fitTo(c);
            pv_viewer.requestRedraw()
        }
    }, isStructureEnabled: function (a) {
        return pv_viewer.get(a) ?
            !0 : !1
    }
};
PLIP.AbstractViewerNGL = {
    newBondMesh: function (a) {
        return new NGL.Shape(a, {disableImpostor: !0})
    }, addWaterCircle: function (a, b, c, d, e) {
        a.addEllipsoid(b, d, c, [c, 0, 0], [0, c, 0]);
        e && ngl_stage.addComponentFromObject(a).addRepresentation("buffer")
    }, addBondLine: function (a, b, c, d, e, f, g) {
        for (var h = 1; h <= d; h++) a.addCylinder(midpoint3d(b, c, (2 * h - 2) / (2 * d - 1)), midpoint3d(b, c, (2 * h - 1) / (2 * d - 1)), f, e);
        g && ngl_stage.addComponentFromObject(a).addRepresentation("buffer")
    }, removeMesh: function (a) {
        removeNGLComponentByName(a)
    }, fitView: function (a,
                          b, c) {
        if (this.isStructureEnabled(a)) {
            var d = "";
            b && (ligand_positions = b.map(function (a) {
                return a.position
            }), d += "((" + ligand_positions.join(" or ") + ") and :_)");
            if (void 0 !== c[0]) {
                b = {};
                for (var e in c) {
                    var f = c[e], g = f.position;
                    f = f.chain;
                    b[f] ? b[f].push(g) : b[f] = [g]
                }
                for (f in b) "" != d && (d += " or "), d += "((" + b[f].join(" or ") + ") and :" + f + ")"
            }
            getNGLComponentByName(a).autoView(d, 1E3)
        }
    }, isStructureEnabled: function (a) {
        return getNGLComponentByName(a) ? !0 : !1
    }
};

function togglePLIPLigandContacts(a, b, c) {
    $("#togglePLIPLigandContacts_" + a).hasClass("glyphicon-chevron-down") ? showPLIPLigandContacts(a, b, c) : hidePLIPLigandContacts(a, b, c)
}

function showPLIPLigandContacts(a, b, c) {
    $("#PLIPligandContacts_" + a).show(100);
    setTimeout(function () {
        $("#togglePLIPLigandContacts_" + a).addClass("glyphicon-chevron-up").removeClass("glyphicon-chevron-down")
    }, 100);
    void 0 !== c && (showBonds(b, c), fitToBonds(b, c))
}

function hidePLIPLigandContacts(a, b, c) {
    $("#PLIPligandContacts_" + a).hide(100);
    setTimeout(function () {
        $("#togglePLIPLigandContacts_" + a).addClass("glyphicon-chevron-down").removeClass("glyphicon-chevron-up")
    }, 100);
    void 0 !== c && hideBonds(b, c)
}

var plip_interactions_fit_bonds = !0;

function togglePlipInteractions(a, b, c) {
    $("#togglePlipInteractions_" + a).hasClass("glyphicon-chevron-down") ? ($("#togglePlipInteractions_" + a).removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up"), $("#plipInteractions_" + a).show(), PLIP.AbstractViewer.isStructureEnabled(b) && (plip_interactions_fit_bonds = !1, $.when($("#plipInteractions_" + a + " input").each(function (a, b) {
        $(b).prop("checked", !0).trigger("change")
    })).done(function () {
        fitToBonds(b, c)
    }), plip_interactions_fit_bonds = !0)) : ($("#togglePlipInteractions_" +
        a).addClass("glyphicon-chevron-down").removeClass("glyphicon-chevron-up"), $("#plipInteractions_" + a).hide(), $("#plipInteractions_" + a + " input").each(function (a, b) {
        $(b).prop("checked", !1).trigger("change")
    }))
}

function changeLigandContacts4Checkbox(a, b, c, d) {
    d = $("#chainContacts4_" + d);
    var e = d.prop("checked");
    PLIP.AbstractViewer.isStructureEnabled(a) ? (plip_residues.getResiduesByResId(a, c).forEach(function (a) {
        e ? a.show(!1) : a.hide(!1)
    }), b = all_ligands.getLigandById(a, b), e ? b.show(!0, !1) : b.hide(!0, null, !1), plip_residues.redraw(a)) : d.prop("checked", !1)
}

function clickLigandContacts4Checkbox(a, b, c, d) {
    PLIP.AbstractViewer.isStructureEnabled(a) && ($("#chainContacts4_" + d).each(function (a, b) {
        $(b).prop("checked", !$(b).prop("checked")).trigger("change")
    }), $("#chainContacts4_" + d).prop("checked") && PLIP.AbstractViewer.fitView(a, [all_ligands.getLigandById(a, b)], plip_residues.getResiduesByResId(a, c)))
}

function toggleResidueContacts(a, b, c) {
    $("#toggleResidueContacts4_" + a).hasClass("glyphicon-chevron-down") ? ($("#toggleResidueContacts4_" + a).removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up"), $("#residueContacts4_" + a).show(), PLIP.AbstractViewer.isStructureEnabled(c) && ($("#residueContacts4_" + a + " input").each(function (a, b) {
        $(b).prop("checked", !0).trigger("change")
    }), ligand = all_ligands.getLigandById(c, b), PLIP.AbstractViewer.fitView(c, [ligand], ligand.getResidues()))) : ($("#toggleResidueContacts4_" +
        a).removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down"), $("#residueContacts4_" + a).hide(), PLIP.AbstractViewer.isStructureEnabled(c) && $("#residueContacts4_" + a + " input").each(function (a, b) {
        $(b).prop("checked", !1).trigger("change")
    }));
    updatecols()
}

function toggleLigandGroup(a, b, c) {
    $("#toggleLigandGroup_" + a).hasClass("glyphicon-chevron-down") ? ($("#toggleLigandGroup_" + a).addClass("glyphicon-chevron-up").removeClass("glyphicon-chevron-down"), $("#ligandGroup_" + a).show(), centerOnLigand(b, c)) : ($("#toggleLigandGroup_" + a).addClass("glyphicon-chevron-down").removeClass("glyphicon-chevron-up"), $("#ligandGroup_" + a + " .glyphicon-chevron-up").each(function (a, b) {
        $(b).trigger("click")
    }), $("#ligandGroup_" + a).hide());
    updatecols()
}

function toggleAllLigands(a) {
    $("#toggleAllLink").hasClass("glyphicon-chevron-down") ? ($("#toggleAllLink,span[id^=toggleLigandGroup_]").removeClass("glyphicon-chevron-down").addClass("glyphicon-chevron-up"), $("div[id^=ligandGroup_]").show()) : ($("#toggleAllLink,span[id^=toggleLigandGroup_]").removeClass("glyphicon-chevron-up").addClass("glyphicon-chevron-down"), $("div[id^=ligandGroup_]").hide())
};