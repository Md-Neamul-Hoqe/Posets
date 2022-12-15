<!-- link jquery.js file before use jquery to solve the error "$ is not defined." in console" -->
<!-- <script src="./styles/js/addJs/jquery-3.6.1.js"></script> -->

<!-- Script.js not work properly with php but php can -->

<script>
    /* Set 5 as Initial Value on page load (index.php) */
    const morder = document.getElementById("MOrder").value = <?php echo isset($_GET["submitOrder"]) ? $_GET['morder'] : 5; ?>;
    // const morder = document.getElementById("MOrder").value = <?php // echo isset($_GET["submitOrder"]) || isset($_GET["Order"]) ? $_GET['morder'] : 5; 
                                                                ?>;
    // document.getElementById("PosetOutput").hidden = true;
    // document.getElementById("Search").hidden = true;

    // <?php // echo isset($_GET["submitOrder"]) ?
        //     'const PosetIndex = document.getElementById("PosetOutput").getContext("2d"),
        //       PO_width = (document.getElementById("PosetOutput").width = (morder + 1) * 50),
        //            PO_height = (document.getElementById("PosetOutput").height = (morder + 1) * 50);' : 0 
        ?>
    // PosetreDraw(); 
    function PosetreDraw() {
        /* Clear The Canvas */
        PosetIndex.clearRect(0, 0, PO_width, PO_height);

        // document.getElementById("PosetOutput").hidden = false;
        // document.getElementById("Search").hidden = false;
        if (
            selectedElements != undefined &&
            selectedElements != null &&
            selectedElements.length
        ) {
            // console.table(selectedElements);
            /* Re-Draw The Selected Elements & Relations */
            for (let i = 0; i < SELength; i++) {
                /* Fill The Points */
                PosetIndex.beginPath(),
                    PosetIndex.arc(
                        selectedElements[i][0] * x,
                        selectedElements[i][1] * x,
                        radius,
                        0,
                        2 * Math.PI
                    ),
                    PosetIndex.fill();
                // console.log("The point ", i, " is restored.");

                /* Re-Draw The Covers of i if Exist */
                if (
                    selectedElements[i][2] != undefined &&
                    selectedElements[i][2] != null &&
                    selectedElements[i][2].length
                ) {
                    // console.log("=================\nThe Covers of", i, "are "),
                    //   console.table(selectedElements[i][2]);
                    /* If Any Relation Found To 'i' get Length of the Relation List */
                    var jL = selectedElements[i][2].length; // Use var to avoid undefined error of jL outside the block

                    for (let j = 0; j < jL; j++) {
                        PosetIndex.beginPath(),
                            PosetIndex.moveTo(
                                selectedElements[i][0] * x,
                                selectedElements[i][1] * x
                            );
                        PosetIndex.lineTo(
                            selectedElements[selectedElements[i][2][j]][0] * x,
                            selectedElements[selectedElements[i][2][j]][1] * x
                        );
                        // console.log(i, "is covered by ", selectedElements[i][2][j]);
                        PosetIndex.stroke();
                        // console.log("The Relation is restored.");
                    } /* Covers of i Re-Drawn */
                } else {
                    // console.log("No Cover is Found For ", i);
                }
            } // Re-stored All
            // console.log(
            //   "=====================\nRestored Everything.\n====================="
            // );
        } else {
            // console.log(
            //   "=====================\n=====================\nNothing To Resotred. All Elements With Their Relations/Covers Removed."
            // );
        }
    } // reDraw()
    if (<?php echo isset($_GET["submitPoset"]) ? 1 : 0 ?>) {
        PosetreDraw();
    }

    /**
     * 
     * @@@@@@@@@@@@@@@@@ DRAWING TOOL @@@@@@@@@@@@@@@@@@
     * 
     */

    /* =========
                        Initialising The Variables
                        =========== */
    // The Global Variables
    // const morder = document.getElementById("MOrder").value = <?php // echo isset($_GET[""]) ? $_GET['morder'] : 5; 
                                                                ?>;
    const x = 50, // The gride start from x
        radius = 5,
        width = (document.getElementById("poset").width = (morder + 1) * x),
        height = (document.getElementById("poset").height = (morder + 1) * x),
        poset = document.getElementById("poset").getContext("2d");
    var xCoord = 0,
        yCoord = 0,
        type = "Connected",
        PxCurrent = 0,
        PyCurrent = 0,
        PStart = -1,
        PEnd = -1,
        isHovered = false,
        keyPosY = 0,
        keyPosX = 0,
        Draw = 0,
        SELength = 0, // Array To Store The Number of Selected Nodes
        selectedElements = [], // Array To Store The Selected Nodes
        HEs = [],
        ConnectedTo = [],
        DistanceFromGrids = [], // Distance From Mesh Poits To Mouse Selection
        XYPoints = []; // Mesh Points with max info
    /* =========
                        Initialisation of Canvas
                        =========== */

    /* Set The Name as Numerical value of the grids */
    for (let i = 0; i < morder; i++) {
        XYPoints[i] = []; // To increase The Dimension by 1
        // for (let j = morder - 1; j >= 0; j--) {
        for (let j = 0; j < morder; j++) {
            XYPoints[i][j] = [];
            /* The Points Located In Frame Like Mesh Gride */
            XYPoints[i][j][0] = i + 1; // X Coordinate of Mouse Down Click
            XYPoints[i][j][1] = j + 1; // Y Coordinate of Mouse Down Click
            XYPoints[i][j][2] = false; // isFilled
        }
    }

    document.getElementById("ShowErrors").hidden = true; /* Hidden By Default */

    /* Initialise the canvas frame with the grids */
    PosetInitial();

    function PosetInitial() {
        /* Canvas Border Outside the Gride system */
        poset.beginPath(); // Important
        poset.rect(x - 12, x - 12, width - x * 1.5, height - x * 1.5);
        poset.stroke();
        for (let i = 0; i < morder; i++) {
            for (let j = 0; j < morder; j++) {
                poset.beginPath(),
                    poset.arc(
                        XYPoints[i][j][0] * x,
                        XYPoints[i][j][1] * x,
                        radius,
                        0,
                        2 * Math.PI
                    ),
                    poset.stroke();
            }
        }
        // Initialising The Coords by small circles with radius = "$radius"

        /* Side Headings & Titles */
        poset.save(); // Save all previously desinged to change design only for next poset
        (poset.textAlign = "center"),
        (poset.font = "12px 'Arial'"),
        poset.rotate((90 * Math.PI) / 180); // To rotate frame top-left (0, 0) to bottom-left (0, 0)
        (max = "Maximal Elements"), poset.fillText(max, width / 2, -3);
        /* (-ve) Height means upward */
        (max = "Minimal Elements"), poset.fillText(max, width / 2, -height + 25);
        for (let i = 0; i < morder; i++) {
            // Coordinates Labeling
            (H_no = i), poset.fillText("H:" + H_no, 17, -(x * (H_no + 1) - 6));
            (W_no = i), poset.fillText("W:" + W_no, x * (W_no + 1), -20);
        }
        poset.restore(); // restore The saved design
    } /* Poset Initial */

    /* ============================================================ */
    /* reDraw canvas with all drawn elements & their covers */
    /* ============================================================ */
    function reDraw() {
        /* Clear The Canvas */
        poset.clearRect(0, 0, width, height);
        // console.log(
        //   "=====================\nThe canvas Cleared.\n====================="
        // );

        /* Re-Draw The Canvas Initially */
        PosetInitial();
        // console.log("The grid circles Restored by PosetInitial()."),
        //   console.log("ReDrawing...", "\nThe selected Elements to be reDrawn.");

        if (
            selectedElements != undefined &&
            selectedElements != null &&
            selectedElements.length
        ) {
            // console.table(selectedElements);
            /* Re-Draw The Selected Elements & Relations */
            for (let i = 0; i < SELength; i++) {
                /* Fill The Points */
                poset.beginPath(),
                    poset.arc(
                        selectedElements[i][0] * x,
                        selectedElements[i][1] * x,
                        radius,
                        0,
                        2 * Math.PI
                    ),
                    poset.fill();
                // console.log("The point ", i, " is restored.");

                /* Re-Draw The Covers of i if Exist */
                if (
                    selectedElements[i][2] != undefined &&
                    selectedElements[i][2] != null &&
                    selectedElements[i][2].length
                ) {
                    // console.log("=================\nThe Covers of", i, "are "),
                    //   console.table(selectedElements[i][2]);
                    /* If Any Relation Found To 'i' get Length of the Relation List */
                    var jL = selectedElements[i][2].length; // Use var to avoid undefined error of jL outside the block

                    for (let j = 0; j < jL; j++) {
                        poset.beginPath(),
                            poset.moveTo(
                                selectedElements[i][0] * x,
                                selectedElements[i][1] * x
                            );
                        poset.lineTo(
                            selectedElements[selectedElements[i][2][j]][0] * x,
                            selectedElements[selectedElements[i][2][j]][1] * x
                        );
                        // console.log(i, "is covered by ", selectedElements[i][2][j]);
                        poset.stroke();
                        // console.log("The Relation is restored.");
                    } /* Covers of i Re-Drawn */
                } else {
                    // console.log("No Cover is Found For ", i);
                }
            } // Re-stored All
            // console.log(
            //   "=====================\nRestored Everything.\n====================="
            // );
        } else {
            // console.log(
            //   "=====================\n=====================\nNothing To Resotred. All Elements With Their Relations/Covers Removed."
            // );
        }
    } // reDraw()

    /* ============================================================ */
    /* Find Indices of Least Distanced (< radius) of two Nearest (minDistance) Points from "selectedElements" matrix */
    /* ============================================================ */
    function LeastDistance(xCurrent, yCurrent) {
        let Start = -1,
            End = -1;
        let X1, Y1, X2, Y2;
        let minDistance = Number.MAX_SAFE_INTEGER,
            DistanceFromPointToLine = Number.MAX_SAFE_INTEGER,
            DistanceFromXY1ToXY2 = Number.MAX_SAFE_INTEGER;
        /* X Coordinate of Clicked Position */
        let xCCo = Math.floor(xCurrent / 50);
        let count = 0; // selected Elements below xCurrent
        selectedElements.forEach((element) => {
            if (element[0] <= xCCo) {
                count++;
            }
        });
        /* Get Two Points To Relate */
        for (let i = count - 1; i >= 0; i--) {
            /* Set X1, Y1 as 1st point down xCurrent */
            X1 = selectedElements[i][0] * x;
            Y1 = selectedElements[i][1] * x;

            /* Find Suitable X2, Y2 for 2nd point in up xCurrent */
            for (let j = count; j < SELength; j++) {
                if (selectedElements[j][0] * x > X1) {

                    /* take End point */
                    X2 = selectedElements[j][0] * x;
                    Y2 = selectedElements[j][1] * x;

                    /* Disntance From Start to End */
                    DistanceFromXY1ToXY2 = Math.sqrt(
                        Math.pow(X1 - X2, 2) + Math.pow(Y1 - Y2, 2)
                    );

                    /* Distance From Hovered Point To Start-End Line */
                    DistanceFromPointToLine =
                        Math.abs(
                            (Y2 - Y1) * xCurrent -
                            (X2 - X1) * yCurrent +
                            (Y1 * (X2 - X1) - X1 * (Y2 - Y1))
                        ) / DistanceFromXY1ToXY2;

                    /* connect points with min distanced & must be less than "radius" distance */
                    if (
                        DistanceFromXY1ToXY2 < minDistance &&
                        DistanceFromPointToLine <= radius
                    ) {
                        /* =========================================== */
                        /* Check! is Trivial Case [Cover of Sub-cover is also a cover] */
                        /* =========================================== */

                        /* var isCovered = false => i & j is connectable */
                        var isCovered = (IsConnectable(i, j) === true) ? true : false;
                        // console.log("Check! Can We Relate?", i, j, !isCovered); // If Covered We can't Relate.

                        /* @@@@@@@@@@@@@@@@@@@@@@@ */
                        /* Making Relation */
                        if (isCovered) {
                            document.getElementById("ShowErrors").innerHTML = "Can't Relate Them.";
                            // document.getElementById("ShowErrors").innerHTML = "Transitivity is trivial. Trivial cases are ignored for drawing simplicity.";
                            // document.getElementById("ShowErrors").hidden = false;
                        } else {
                            /* Save Smallest Path & Update In Every Possible Path */
                            // console.log("Start & End Updated. Since (minDistance =)", minDistance, ">", DistanceFromXY1ToXY2, "(DistanceFromXY1ToXY2)");
                            minDistance = DistanceFromXY1ToXY2;

                            /* Update the points To Draw */
                            poset.beginPath(); /* Use beginPath() to prevent connection from previous path */
                            poset.moveTo(X1, Y1);
                            poset.lineTo(X2, Y2);

                            /* Save The Indices of X1, Y1 and X2, Y2 to return to finally drawn */
                            (Start = i), (End = j);
                            // console.log("Start =", Start, " End = ", End);
                            // console.table(selectedElements);
                            // console.table(selectedElements[0][2]);
                        } // The Relation is Trivial
                        /* Making Relation END */
                        /* @@@@@@@@@@@@@@@@@@@@@@@ */

                    } // Click near the connection line or on nearest points // check is minDistanced
                } // Relate only in upword direction
            } // For loop of j -> SELength Closed
        } // For loop of i -> 0 Closed
        return [Start, End];
    } // LeastDistance()

    /* For Hover Effect */
    function LeastDistanceShow(xCurrent, yCurrent) {
        let Start = -1,
            End = -1;
        let X1, Y1, X2, Y2;
        let minDistance = Number.MAX_SAFE_INTEGER,
            DistanceFromPointToLine = Number.MAX_SAFE_INTEGER,
            DistanceFromXY1ToXY2 = Number.MAX_SAFE_INTEGER;
        /* X Coordinate of Clicked Position */
        let xCCo = Math.floor(xCurrent / 50);

        /* selected Elements below xCurrent */
        let count = 0;
        selectedElements.forEach((element) => {
            if (element[0] <= xCCo) {
                count++;
            }
        });

        /* Get Two Points From SelectedElements To Relate */
        for (let i = count - 1; i >= 0; i--) {
            /* Set X1, Y1 as 1st point down xCurrent */
            X1 = selectedElements[i][0] * x;
            Y1 = selectedElements[i][1] * x;

            /* Find Suitable X2, Y2 for 2nd point in up-xCurrent */
            for (let j = count; j < SELength; j++) {
                if (selectedElements[j][0] * x > X1) {

                    /* take End point */
                    X2 = selectedElements[j][0] * x;
                    Y2 = selectedElements[j][1] * x;

                    /* Disntance From Start to End */
                    DistanceFromXY1ToXY2 = Math.sqrt(
                        Math.pow(X1 - X2, 2) + Math.pow(Y1 - Y2, 2)
                    );

                    /* Distance From Hovered Point To Start-End Line */
                    DistanceFromPointToLine =
                        Math.abs(
                            (Y2 - Y1) * xCurrent -
                            (X2 - X1) * yCurrent +
                            (Y1 * (X2 - X1) - X1 * (Y2 - Y1))
                        ) / DistanceFromXY1ToXY2;

                    /* Find The line of Nearest Two Elements (Least Distanced) in "radius" distance */
                    if (
                        DistanceFromXY1ToXY2 < minDistance &&
                        DistanceFromPointToLine <= radius
                    ) {
                        /* =========================================== */
                        /* Check! is Trivial Case [Cover of Sub-cover is also a cover] */
                        /* =========================================== */

                        var isCovered = (IsConnectable(i, j) === true) ? true : false;
                        // console.log("Check! Can We Relate?", i, j, !isCovered);

                        /* @@@@@@@@@@@@@@@@@@@@@@@ */
                        /* Hover Effect */
                        // isHovered = true;
                        /* Start & End Directly Related Now? if Yes => No Hover Effect. */
                        if (
                            selectedElements[i][2] != undefined &&
                            selectedElements[i][2] != null &&
                            selectedElements[i][2].length
                        ) {
                            var foundEnd = selectedElements[i][2].findIndex(function(element) {
                                return element == j;
                            });
                        } else {
                            foundEnd = -1;
                        }

                        if (isCovered || foundEnd !== -1) {
                            /* Update Distance but not Start & End. Becouse they directly connected or transitively Covered. */
                            minDistance = DistanceFromXY1ToXY2;
                            // document.getElementById("ShowErrors").hidden;
                        } else {
                            /* Save Smallest Path & Update In Every Possible Path */
                            minDistance = DistanceFromXY1ToXY2;

                            /* Save The Indices of X1, Y1 and X2, Y2 to return to finally drawn */
                            (Start = i), (End = j);
                        } // The Relation is Trivial
                        /* Hover Effect END */
                        /* @@@@@@@@@@@@@@@@@@@@@@@ */

                    } // Click near the connection line or on nearest points // check is minDistanced
                } // Relate only in upword direction
            } // For loop of j -> SELength Closed
        } // For loop of i -> 0 Closed
        return [Start, End];
    } // LeastDistance()

    function ShowConnection(xCurrent, yCurrent) {
        /* Is Connectable In Hover */
        let [Start, End] = LeastDistanceShow(xCurrent, yCurrent);
        /* If Possible Elements Found To Relate */
        if (Start > -1 && End > -1) {
            // document.getElementById("Coords").innerHTML =
            //     "The Nearest Points are: [" + Start + " ," + End + "]";
            // console.log("The Nearest Points are: [", Start, " ,", End, "]");

            /* If New Pair of Elements Found */
            if (PStart != Start || PEnd != End) {
                // console.log(PStart, "!=", Start, "&", PEnd, "!=", End);
                Draw = 1;
                /* Remove previous Dashed Lines */
                reDraw();
                /* Draw The New Dashed Line */
                poset.save();
                poset.beginPath(); /* Use beginPath() to prevent connection from previous path */
                poset.moveTo(
                    selectedElements[Start][0] * x,
                    selectedElements[Start][1] * x
                );
                poset.lineTo(selectedElements[End][0] * x, selectedElements[End][1] * x);
                poset.setLineDash([3, 3]);
                poset.stroke();
                poset.restore();
            } else {
                /* If Same Points Found Do Nothing i.e no draw or no reDraw() */
            }
        } else if (Draw == 1) {
            /* If Dashed line Already Drawn & Next Mouse move Detect Nothing i.e. Start & End = -1 */
            Draw = 0;
            reDraw();
        } // if (Start != -1 && End != -1) END
        /* Save The Previous Mouse Position */
        // console.log("Previous Mouse Position is Saved in ", Start, End);
        (PStart = Start), (PEnd = End);
    } // ShowConnection(xCurrent, yCurrent) END

    /* ============================================================ */
    /* Is Start & End connectable? return true or false */
    /* ============================================================ */
    function IsConnectable(Start, End) {
        // console.log("Here Start =", Start, " & End =", End);
        /* is Start Cover By Any Elements? */
        if (
            selectedElements[Start][2] != undefined &&
            selectedElements[Start][2] != null &&
            selectedElements[Start][2].length
        ) {
            // console.log(Start, "is covered by: ");
            // console.table(selectedElements[Start][2]);
            let StartL = selectedElements[Start][2].length;
            for (let ii = 0; ii < StartL; ii++) {
                /* ii'th Element is cover of "Start" */
                let cover = selectedElements[Start][2][ii];
                // console.log("Searching ",cover);

                /* is "cover" also cover of "End" => made a triangle as 'CUP' shape */
                if (
                    selectedElements[End][2] != undefined &&
                    selectedElements[End][2] != null &&
                    selectedElements[End][2].length
                ) {
                    // console.log(End, "is covered by: "),
                    // console.table(selectedElements[End][2]);

                    /* if "cover" is also cover of End */
                    let foundI = selectedElements[End][2].findIndex(function(element) {
                        return element === cover;
                    });
                    if (foundI != -1) {
                        // console.log("Can't Relate.");
                        return true;
                    }
                }

                /**
                 * @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                 * May Possible To Simplify From Here [Do the same in the function?]
                 * @@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
                 */

                /* Is End Cover "Start's Cover" or any of it'a cover. => made a 'initial-moon' shape*/
                // console.table(selectedElements);
                // console.table(selectedElements[ii]);
                if (
                    selectedElements[cover][2] != undefined &&
                    selectedElements[cover][2] != null &&
                    selectedElements[cover][2].length
                ) {
                    // console.log(cover, "is covered by: "),
                    //   console.table(selectedElements[cover][2]);

                    /* is 2nd Element (Start's Cover) is covered by End */
                    let foundI = selectedElements[cover][2].findIndex(function(element) {
                        // console.log(element, " ?= ", End);
                        return element === End;
                    });
                    // console.log(End, "is 2nd gen? if yes then with?: ");
                    // console.log(foundI);

                    if (foundI !== -1) {
                        // console.log(End, "cover", cover, "\nSo Can't Relate.");
                        return true; // Not Covered
                    } else {
                        // console.log(
                        //   "At least One Element Found Which Cover the cover of Start & Less Than End."
                        // );
                        /* If All is > End Then Start & End Can be connected */
                        /* cover is not directly covered by End */
                        let relL = selectedElements[cover][2].length;
                        for (let iii = 0; iii < relL; iii++) {
                            let rel1 = selectedElements[cover][2][iii];
                            /* Is rel1 Cover End then must be End is not cover rel1 => End is not cover "cover" (Cover of Start) => End is not cover Start */
                            // return (rel1 < End && relations(rel1))? true : false;
                            if (rel1 < End && relations(rel1)) return true;

                            /* ==================================================== */
                            /* Find The Relations by Recurcive Sub-Function */
                            /* ==================================================== */
                            function relations(rel1) {
                                if (
                                    selectedElements[rel1][2] != undefined &&
                                    selectedElements[rel1][2] != null &&
                                    selectedElements[rel1][2].length
                                ) {
                                    // console.log(rel1, "is covered by"),
                                    //     console.table(selectedElements[rel1][2]);
                                    let rel1L = selectedElements[rel1][2].length;

                                    /* Check if rel1 in covered by END */
                                    let foundII = selectedElements[rel1][2].findIndex(function(
                                        element
                                    ) {
                                        return element == End;
                                    });
                                    if (foundII !== -1) {
                                        // console.log(End, "is cover", rel1);
                                        return true;
                                    } else {
                                        // console.log("Is", rel1, " cover / covered by any element?");
                                        for (let iii = 0; iii < rel1L; iii++) {
                                            let rel2 = selectedElements[rel1][2][iii];
                                            if (rel2 < End) {
                                                /* If rel2 not cover End Or Equal End Then Search Again */
                                                return relations(rel2);
                                            } else if (rel2 == End) {
                                                /* If rel2 == End */
                                                return true;
                                            }
                                        }
                                    }
                                }
                            } // relations(cover) End
                        }
                    }
                }
            } // For loop (ii->StartL) End
        }

        /* is Any Elements Cover By Start & End ? made a triangle as 'U' shape */
        for (let i = 0; i < Start; i++) {
            if (
                selectedElements[i][2] != undefined &&
                selectedElements[i][2] != null &&
                selectedElements[i][2].length
            ) {
                // console.log(i, "is covered by: "),
                // console.table(selectedElements[i][2]);
                /* Is 'Start' covers i'th element */
                let foundS = selectedElements[i][2].findIndex(
                    (element) => element == Start
                );
                /* Is 'End' covers i'th element */
                let foundE = selectedElements[i][2].findIndex(
                    (element) => element == End
                );

                /* Is Start & End Cover Same Element i */
                if (foundS > -1 && foundE > -1) {
                    // console.log("Can't Relate.");
                    return true;
                }
                // else {
                //   console.log("No Element is Found Which is covered by ", Start, End);
                // }
            } // if(selectedElements[i][2] != undefined|null|0)
        }
        // return isCovered;
    } // IsConnectable() END

    /* ============================================================  */
    /* Find Distance From Clicked Point To Others Gride Points */
    /* ============================================================  */
    function findDistFromGrids(xCurrent, yCurrent) {
        // console.log("In findDistFromGrids() ", xCurrent, yCurrent);
        // Current Point (x, y)
        for (let k = 0, i = 0; i < morder; i++) {
            for (let j = 0; j < morder; j++, k++) {
                (X = XYPoints[i][j][0] * x), (Y = XYPoints[i][j][1] * x);
                DistanceFromGrids[k] = Math.sqrt(
                    Math.pow(X - xCurrent, 2) + Math.pow(Y - yCurrent, 2)
                );
            }
        }
        d = Math.min(...DistanceFromGrids);
        K = DistanceFromGrids.indexOf(d);
        (xCoord = Math.floor(K / morder)), (yCoord = K % morder); // Calculate the XYPoints index of the value from the DistanceFromGrids index
        return [xCoord, yCoord, K];
    } // findDistFromGrids(xCurrent, yCurrent) END

    /* ============================================================  */
    /* Update The Covers Of the Elements According To The Value Of (p, U)  */
    /* ============================================================  */
    function UpdateRelations(p, U) {
        /* If Removed Selected Elements */
        /* Will be deleted from 'p' Position */
        if (U < 0) {
            // console.log(
            //   "#########################\nTo be Updated For Deletion from",
            //   p,
            //   "\n########################"
            // ),
            // console.table(selectedElements);
            for (let j = 0; j < SELength - 1; j++) {
                /* Heightest element has no cover so SELength-1 */
                /*
                      => If exist any relation with p find that.
                      => remove the relations
                      => decreas the relational value (>p) by 1
                      => remove p'th element
                      */
                if (
                    selectedElements[j][2] != undefined &&
                    selectedElements[j][2] != null &&
                    selectedElements[j][2].length &&
                    j != p
                ) {
                    // console.log("Cover of ", j, "============================\n");
                    /* j'th element has relation to p */
                    /* Search Is j'th element Covered by (<p & j->p) 'p' */
                    let m = selectedElements[j][2].findIndex((element) => element == p);
                    if (m > -1 && j < p) {
                        /* If 'p' is cover j */
                        // console.log("Remove ", p, "-cover of", j);
                        /* Delete the p-cover */
                        selectedElements[j][2].splice(m--, 1);
                    } // m > -1 && j < p

                    // Here p!=j by default in 389 condition
                    // console.log(
                    //   "The Covers of ",
                    //   j,
                    //   "are: \n[Need To be Dicreases by 1 Which are >",
                    //   p,
                    //   "]"
                    // ),
                    //   console.table(selectedElements[j][2]);
                    /* 
                    Search: 
                    => Is j'th element Covered by SEs whose are >p. (Since All SEs > p will reduce by 1). 
                    => If found reduce Relational values by 1 
                    */
                    let RL = selectedElements[j][2].length;
                    m = selectedElements[j][2].findIndex((element) => element > p);
                    if (m > -1) {
                        /* j'th (>p) element's relational values will be reduced by 1 */
                        for (let q = m; q < RL; q++) {
                            // console.log(selectedElements[j][2][q], "is Decreased to");
                            selectedElements[j][2][q]--;
                            // console.log(selectedElements[j][2][q], "in", j);
                        }
                        //   console.table(selectedElements[j][2]);
                    } // q->RL
                } // if(selectedElements[j][2] != undefined)
            } // j -> SELength-1
        } else if (U > 0) {
            /* If a new element inserted in 'p' Position */
            // console.log(
            //   "#######################\nTo be Updated For Insertion in",
            //   p,
            //   "\n########################"
            // ),
            // console.table(selectedElements);
            /* Update for every Elements */
            for (let j = 0; j < SELength - 1; j++) {
                /* Heightest element has no cover so SELength-1 */
                /*
                      => If exist any relation to >= p find that.
                      => increase the relational value by 1
                      */
                if (
                    selectedElements[j][2] != undefined &&
                    selectedElements[j][2] != null &&
                    selectedElements[j][2].length &&
                    j != p
                ) {
                    let pL = selectedElements[j][2].length;
                    /* Increase Relational Values by 1, Since Inserted in p */
                    let m = selectedElements[j][2].findIndex((element) => element >= p);
                    // If covers not sorted then use filter
                    /* Since All Covers are sorted in accending order */
                    if (m > -1) {
                        // console.log("Increase Them by 1 whose are >=", p),
                        // console.table(selectedElements[j]);
                        for (let ji = m; ji < pL; ji++) {
                            // console.log(selectedElements[j][2][ji], "is increased to");
                            selectedElements[j][2][ji]++;
                            // console.log(selectedElements[j][2][ji]);
                        }
                        // console.log("Updated Now"),
                        // console.table(selectedElements[j]);
                    } //(m > -1)
                } // if(selectedElements[j][2] != undefined)
            } // j-> SELength-1
        } // U>0
    } /* UpdateRelations(p) END */

    /* ============================================================  */
    /* Fill The Clicked Unfilled Gride Point Or Unfill Clicked Filled Gride Point  */
    /* ============================================================  */
    function fillOrUnFill(xCoord, yCoord) {
        // console.log("In fillOrUnFill(): ", xCoord, yCoord);

        /* Find the click Point's Position in Selected Elements */
        function FindI_SE(SEs) {
            return (
                SEs[0] == XYPoints[xCoord][yCoord][0] &&
                SEs[1] == XYPoints[xCoord][yCoord][1]
            );
        }

        /* Fill if not filled Maximum upto MOrder nodes/Elements */
        if (XYPoints[xCoord][yCoord][2] === false && SELength !== morder) {
            // console.log(SELength);
            let X = XYPoints[xCoord][yCoord][0] * x,
                Y = XYPoints[xCoord][yCoord][1] * x;

            /* Now Draw The Node as Filled */
            poset.beginPath(), poset.arc(X, Y, radius, 0, 2 * Math.PI), poset.fill();

            /* Selected Nodes Save to 'selectedElements[]' */
            selectedElements[SELength++] = [
                XYPoints[xCoord][yCoord][0],
                XYPoints[xCoord][yCoord][1],
            ];
            // console.log("The filled point is ");
            // console.table(selectedElements[SELength - 1]);
            XYPoints[xCoord][yCoord][2] = true; /* Fill Successfull */
            var U = 1; /* 1 element will be added in p Position */
            // Total Number of Filled Nodes
            /* Ufill/Remove The node From selectedElements array if already filled */
        } else if (XYPoints[xCoord][yCoord][2] == true) {
            /* Find The Index Of The Point on Selectedelements */
            var p = selectedElements.findIndex(FindI_SE);
            /* Remove All Relations From or To This Point */
            U = -1; // Delete 1 Element
            /* Update The Covers According To p & U if there exist any relation to update */
            if (SELength > 1) {
                /* Update The Relations in selected Elements According To (p, U) */
                UpdateRelations(p, U);
                // console.log("All Relations are Updated. Now Remove", p, "From:"),
                //   console.table(selectedElements);
            }
            /* Now Remove P Element & All Relations From it */
            // console.table(selectedElements[p]);
            // if (
            //   selectedElements[p][2] != undefined &&
            //   selectedElements[p][2] != null &&
            //   selectedElements[p][2].length
            // ) {
            //   console.log("with the relations "), console.table(selectedElements[p][2]);
            // }

            selectedElements.splice(p--, 1);
            SELength--;
            XYPoints[xCoord][yCoord][2] = false; /* Unfill Successfull */
            // console.log("Now Redrawn Poset is:"), console.table(selectedElements);
            reDraw();
        } else {
            /* If More than morder is selected */
            document.getElementById("ShowErrors").hidden = false;
            document.getElementById("ShowErrors").innerHTML = morder + " Elements are already selected.";
            return
        }
        // console.table("XYPoints: ", XYPoints[xCoord][yCoord]);

        /* Sort The Filled Nodes Array */
        /* ================================================== */
        function Sorting2DArray(a, b) {
            if (a[0] == b[0]) {
                /* If in same Height sort accending by Width */
                // console.log("Sorting");
                return a[1] - b[1];
            } else {
                return a[0] - b[0];
            }
        }
        selectedElements.sort(Sorting2DArray); // Again sort about second element if 1st are same
        // console.table(selectedElements);

        let P = selectedElements.findIndex(FindI_SE);
        if (SELength > 2 && P > -1) {
            /* Update The Relations in selected Elements According To (p, U) */
            // console.log("Updating Relations For", P);
            UpdateRelations(P, U);
        }
        /* Copy Nodes To a new array for showConnection() to get hover effect */
        let Copy_nodes_i = 0;
        selectedElements.forEach((element) => {
            // get only filled elements without connections
            HEs[Copy_nodes_i] = [];
            HEs[Copy_nodes_i][0] = element[0];
            HEs[Copy_nodes_i][1] = element[1];
            Copy_nodes_i++;
        });
    } // fillOrUnFill(xCoord, yCoord)

    /** 
     * ===========================================================
     * Connect Two Selected Points (Start (<) End, are selectedElements's index)
     * ===========================================================
     */
    function connectLine(Start, End) {
        if (
            selectedElements[Start][2] != undefined &&
            selectedElements[Start][2] != null
        ) {
            /* Check They are already connected (return Index) or Not (return -1). */
            /* Find The Index Of the element to remove */
            let k = selectedElements[Start][2].findIndex(function(arr) {
                return arr == End;
            });
            if (k != -1) {
                /* If They already Connected Then Remove The Connection. */
                selectedElements[Start][2].splice(k--, 1);
            } else {
                /* If New Connection (k = -1) Then Push To The Array Start.e. connect them. */
                selectedElements[Start][2].push(End);
                connected = 1;
            }
        } else {
            selectedElements[Start][2] = [End];
            connected = 1; /* Start & End Already Connected */
        }
        /* Sort The Covering Elements for convenience searching on letter functions */
        selectedElements[Start][2].sort(function(a, b) {
            return a - b;
        });
        /* A New Connection Created (1) or A Connection Removed (-1) */
        if (connected == 1) {
            poset.stroke();
        } else {
            reDraw();
        }
    } /* ConnectLine(Start, End) END */
    /* ============================================================  */
    /* ############################# Draw Solid Line If Click In Possible Connection ############################# */
    /* ============================================================  */
    function DrawPoset(xCurrent, yCurrent) {
        let K = 0;
        /* Find The Distance From The Mesh Points To The Clicked Point */
        [xCoord, yCoord, K] = findDistFromGrids(xCurrent, yCurrent);
        /* FILL IF NOT CHECKED UNFILLED IF CHECKED */
        if (DistanceFromGrids[K] <= radius + 1) {
            // console.log("In DrawPoset() ", xCoord, yCoord);
            /* Fill The Element As Selected Nodes */
            fillOrUnFill(xCoord, yCoord);
        } else if (SELength > 1) {

            /* Connect Two Points If minimum TWO Elements Selected. */
            let [Start, End] = LeastDistance(xCurrent, yCurrent);

            /* If Found Any Nearest Selected Points To Connect */
            if (Start != -1) {
                // console.log("Connect The Points: ", Start, End);
                let connected = -1; // Let They are not connected.
                /* Define array's new dimention for connectedness if not defined */
                connectLine(Start, End);
            }
        } else {
            document.getElementById("ShowErrors").hidden = false;
            document.getElementById("ShowErrors").innerHTML =
                "Please Select At Least Two Points Before To Connect.";
        }
        /* Show the selectedElements with relations */
        // let TL = selectedElements.length;
        // if (selectedElements.length) {
        //   console.table(selectedElements);
        //   for (let i = 0; i < TL; i++) {
        //     if (
        //       selectedElements[i][2] != undefined &&
        //       selectedElements[i][2] != null &&
        //       selectedElements[i][2].length
        //     ) {
        //       let TLL = selectedElements[i][2].length;
        //       // for(let j = 0; j<TLL; j++){
        //   console.log("Cover of ", i);
        //       console.table(selectedElements[i][2]);
        //       // }
        //     }
        //   }
        // }
    }

    /* ============================================================  */
    /* ############################# GET THE MOUSE DOWN POSITION ################ */
    /* ============================================================  */
    function getMousePosition(canvas, event) {
        // let rect = canvas.getBoundingClientRect(); // Boundary Coords of canvas
        // no click = 0, right click = 2
        if (event.keyCode !== undefined && event.key !== undefined) {
            var mouseClick = event.keyCode || event.key || event.button;
        } else {
            var mouseClick = event.button;
        }
        // console.log(mouseClick);
        // console.log(event.key);
        if (mouseClick === 1) {
            // left click only
            let x = event.offsetX;
            let y = event.offsetY;
            return [x, y];
        } else if (mouseClick === 0) {
            /* For Hover / No Click*/
            let x = event.offsetX;
            let y = event.offsetY;
            return [x, y];
        } else {
            // console.log("Not-Clicked.");
            return [0, 0];
        }

    } /* Mouse Position for an event */

    /* ============================================================  */
    /* ############################# To Draw Line Between Possible Related Points on Click ############################# */
    /* ============================================================  */
    const Canvas = document.querySelector("#poset");
    Canvas.addEventListener("mouseup", function(e) {
        // console.log("Mouse Up Event.");
        let [xCurrent, yCurrent] = getMousePosition(Canvas, e);
        /* "event.which == 1" Only left mouse click is enabled on inner small square area*/

        if (
            xCurrent >= x - 5 &&
            yCurrent > x - 5 &&
            xCurrent <= width - x + 5 &&
            yCurrent <= height - x + 5 &&
            (e.which === 1 || e.button === 0)
        ) {
            // document.getElementById("Coords").innerHTML =
            //     "The Selected Point is: [" +
            //     Math.round(xCurrent / x) +
            //     " ," +
            //     Math.round(yCurrent / x) +
            //     "]";
            // console.log(xCurrent, yCurrent);
            DrawPoset(xCurrent, yCurrent);
        }
        // else {
        // console.log("Do Nothing In Mouse Up Event.");
        // }
    });

    /* ============================================================  */
    /* To Draw Dotted Line Between Possible Related Points on Hover */
    /* ============================================================  */
    const canvasHover = Canvas;
    canvasHover.addEventListener("mousemove", function(e) {
        // console.log("Mouse Move Event.");
        let [xCurrent, yCurrent] = getMousePosition(canvasHover, e);
        /* Active the function on small square after minimum 2 element selection. */
        if (
            xCurrent >= x - 5 &&
            yCurrent >= x - 5 &&
            xCurrent <= width - x + 5 &&
            yCurrent <= height - x + 5 && selectedElements.length > 1
        ) {
            ShowConnection(xCurrent, yCurrent);
        }
        // else {
        // console.log("Do Nothing in Mouse Move.");
        // }
    });

    /* ============================================================  */
    /* Characteristic Matrix Formation  */
    /* ============================================================  */
    function createRelationalTable() {
        SELength = selectedElements.length;
        if (SELength) {
            const CoverMatrixLength = (Math.pow(SELength, 2) - SELength) / 2; // Length of the posetMatrix [Upper Triangular]
            // console.log(CoverMatrixLength);

            /* Transitional Cover Matrix */
            const posetTCM = new Array(CoverMatrixLength).fill(0); // Initialize as 0
            const SEsC = selectedElements;
            let idx = 0;

            /* Transit Cover Matrix */
            for (let j = morder - 3; j >= 0; j--) {
                /* If The (j) element has no relation, Needn't check further */
                // var PTCM = SEsC[j][2].length;
                if (
                    SEsC[j][2] != undefined &&
                    SEsC[j][2] != null &&
                    SEsC[j][2].length
                ) {
                    // console.log(SEs2);
                    // console.log('j = ', j);
                    for (let k = j + 1; k < morder - 1; k++) {

                        /* Is j -> k ? */
                        let idxj = SEsC[j][2].findIndex(function(a) {
                            // console.log("j = ", j, "\n");
                            // console.table(a, "?=", j + 1);
                            return a == k;
                        })

                        /* If j -> k then */
                        if (idxj != -1) {
                            // console.log('k = ', k);
                            // console.log(j, '->', k, " Found in idx = ", idxj);
                            /* If Next element (k) has no relation, Needn't check further */
                            if (
                                SEsC[k][2] != undefined &&
                                SEsC[k][2] != null &&
                                SEsC[k][2].length
                            ) {
                                for (let l = k + 1; l < morder; l++) {

                                    /* Is k -> l ? */
                                    let idxk = SEsC[k][2].findIndex(function(b) {
                                        // console.log("j+1 = ", j + 1, "\n");
                                        // console.table(b, "?=", k + 1);
                                        return b == l;
                                    })

                                    /* If k -> l then */
                                    if (idxk != -1) {
                                        // console.log('l = ', l);
                                        // console.log(k, '->', l, " Found in idx = ", idxk);
                                        // console.log(j, " Update -> ", l);

                                        /* Append to The Relations */
                                        // PTCM = SEsC[j][2].length;
                                        SEsC[j][2][SEsC[j][2].length] = l;
                                    }
                                }

                                // console.log("Is =? ", SEsC[j][2].length, morder - j - 1);
                                /* If Already All Updated Then 'break' */
                                // PTCM = SEsC[j][2].length;
                                if (SEsC[j][2].length == morder - j - 1) {
                                    // console.log(j, " is connected to ");
                                    // console.table(SEsC[j][2]);
                                    break;
                                }

                            }
                        }
                    }
                }
            }

            /* Find The Cover Matrix */
            for (let i = 0; i < SELength; i++) {
                if (
                    selectedElements[i][2] != undefined &&
                    selectedElements[i][2] != null &&
                    selectedElements[i][2].length
                ) {
                    let RL = selectedElements[i][2].length;
                    for (let k = 0; k < RL; k++) {
                        let cover = SEsC[i][2][k];
                        idx = SELength * i + cover - ((i + 1) * (i + 2)) / 2; // Find the idx from relational values.
                        posetTCM[idx] = 1; // If related assign 1
                    }

                }
            }

            /* To draw in index.php page */
            // console.table(posetTCM);
            // return [posetTCM, selectedElements];
            return posetTCM;
        }
        // else {
        // console.log("Please Select Atleast One Elements.");
        //     // return 0;
        // }
    } /* createRelationalTable() END */

    /* Keyborad Features for Drawing Tool */
    /* Upgrade next for scrolling & Shift Scrolling keyboard feature */
    function DrawingByKeyboard() {
        // console.log("Modal Showing.");
        var PreventFill = true; /* To prevent filled the element on keyboard 'Enter' in "Drawing Tool" button */
        Tab = false;
        document.getElementById("IsKeyboard").innerHTML = "Keyboard Feature is <span class='text-info'>On</span>. [Use mouse for more flexibility & guidlines]<br> <span class='text-dark'> <kbd>Enter</kbd> to select &amp; <kbd>Shift</kbd> + <kbd>Enter</kbd> to relate</span>";

        /* Keyboard Event */
        document.onkeyup = function(e) {
            /* for keyborad, e.which is deprecated */

            let Shift = e.shiftKey;
            // Alt = e.altKey;
            // Ctrl = e.ctrlKey;

            // console.log("key:", e.key);
            switch (e.key) {
                case "ArrowLeft":
                    /* move selection left by 1 */
                    if (keyPosX !== 0) {
                        keyPosX--;
                    }

                    // console.log("Left Key Pressed Here. keyPosX=", keyPosX);
                    break;
                case "ArrowUp":
                    /* move selection up by 1 */
                    if (keyPosY !== morder - 1) {
                        keyPosY++;
                    }

                    // console.log("Up Key Pressed. keyPosY=", keyPosY);
                    break;
                case "ArrowRight":
                    /* move selection Right by 1 */
                    if (keyPosX !== morder - 1) {
                        keyPosX++;
                    }

                    // console.log("Right Key Pressed. keyPosX=", keyPosX);
                    break;
                case "ArrowDown":
                    /* move selection Down by 1 */
                    if (keyPosY !== 0) {
                        keyPosY--;
                    }

                    // console.log("Down Key Pressed. keyPosY=", keyPosY);
                    break;
                case "Enter":
                    /* If 'Tab' press before 'Enter' Or modal show button is clicked by 'Enter' Or Shift key pressed with filled element then Do Nothing on 'Enter' */
                    if (PreventFill || (Tab || (Shift && XYPoints[keyPosY][keyPosX][2]))) {
                        /* Change The value After First 'Enter' Key press */
                        PreventFill = false;
                        break;
                    }

                    /* Else Fill The Selection */
                    DrawPoset(XYPoints[keyPosY][keyPosX][0] * x, XYPoints[keyPosY][keyPosX][1] * x);
                    // console.log(XYPoints[keyPosY][keyPosX][0], XYPoints[keyPosY][keyPosX][1], " (XYPoints) Filled is ", XYPoints[keyPosY][keyPosX][2]);
                    break;
                case "Tab":
                    // console.log("Tab pressed.");
                    // console.log("Tab -> True");
                    Tab = true;
                    reDraw();
                    break;
                case "Escape":
                    Tab = false;
                    break;
            }

            /**
             * If Shift pressed Then keep Selected the Last selection
             * & Hover, If Possible, Connection to next selection 
             * & If Enter pressed then connect the selection with previous selected element
             */


            /* PStart,PEnd = -1 in initial */
            /**
             * If PStart Not set then set it OR
             * If PStart is set But PEnd is not set Then set PEnd First
             * If PStart is set and PEnd also Then PStart reset
             */
            if (Shift && e.key === "Enter") {
                // console.log("Shift Key Pressed & The Element is selected.");

                /* Select The Current Element From Grids */
                function Select(element) {
                    // console.log("element: ", element, " ?= ", XYPoints[keyPosY][keyPosX]);
                    return element[0] === XYPoints[keyPosY][keyPosX][0] &&
                        element[1] === XYPoints[keyPosY][keyPosX][1]
                }

                if (PStart === -1 || PEnd !== -1) {
                    /* PStart was unset (For First Time) Or PEnd was Set (For Next Time, To Reset PStart) */
                    let StartTmp = selectedElements.findIndex(Select);
                    // console.log(StartTmp);
                    if (StartTmp !== -1) {
                        console.log("Start = ", StartTmp, " is set.");
                        PStart = StartTmp;
                    }
                } else {

                    /* PStart is set && PEnd is unset */
                    let StartTmp = selectedElements.findIndex(Select);
                    if (StartTmp !== -1 && StartTmp !== PStart) {
                        console.log("End = ", StartTmp, " is set.");
                        PEnd = StartTmp;

                        /* Connect The Start & End Elements */
                        connectLine((PStart < PEnd) ? PStart : PEnd, (PStart > PEnd) ? PStart : PEnd);
                        console.log("The Elements ", (PStart < PEnd) ? PStart : PEnd, (PStart > PEnd) ? PStart : PEnd, " is Connected Or Dis-connected.");
                        PStart = -1;
                        PEnd = -1;
                    }
                }


            }

            /* Keyboard Selection Effect */
            if (!Tab) {
                reDraw();
                document.getElementById("IsTab").setAttribute("hidden", null);
                poset.beginPath(),
                    poset.arc(
                        XYPoints[keyPosY][keyPosX][0] * x,
                        XYPoints[keyPosY][keyPosX][1] * x,
                        radius + 2,
                        0,
                        2 * Math.PI
                    ),
                    poset.stroke();
            } else {
                console.log("Tab is activated.");
                document.getElementById("IsTab").removeAttribute("hidden");
            }
        }

        /* To reset function's variables on modal close */
        // return
    } /* DrawingByKeyboard() END */

    /* Delete All Elements And There Relations & Update 'XYPoints' Matrix */
    function resetCanvas() {
        document.getElementById("ShowErrors").hidden = true;
        // console.log("@@@@@@@@@@@@@@@@@\nClearing The Canvas...\n@@@@@@@@@@@@@@");

        while (L = selectedElements.length) { // selectedElements.length dicrease to 0 as deletion
            // console.log("SEs Length in resetCanvas(): ", L);
            // console.log("Deleted Element is: ", selectedElements[0][0] * x, selectedElements[0][1] * x);

            /* All Selected Elements Sorted The Re arrange From 0 index */
            DrawPoset(selectedElements[0][0] * x, selectedElements[0][1] * x);
        }
    } /* resetCanvas() END */
</script>