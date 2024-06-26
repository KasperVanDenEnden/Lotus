<div class="row row-cols-md-1 row-cols-lg-3 g-2 m-2">
    <?php
    if (!empty($data)) {
        foreach ($data as $item) {
            if ($item["assigned"] == 0) {
                $assignStatus = '<i class="fa fa-envelope text-primary" aria-hidden="true"></i> <span class="text-muted">Aanmelding ontvangen</span>';
            } else if ($item["assigned"] == 1) {
                $assignStatus = '<i class="fa fa-check text-success" aria-hidden="true"></i> <span class="text-muted">Toegewezen</span>';
            } else if ($item["assigned"] == 2) {
                $assignStatus = '<i class="fa fa-times text-danger" aria-hidden="true"></i> <span class="text-muted">Niet toegewezen</span>';
            } else if ($item["assigned"] == 3) {
                $assignStatus = '<i class="fa fa-times text-danger" aria-hidden="true"></i> <span class="text-muted">Afgemeld</span>';
            }

            $currentDate = new DateTime('now');
            $playDate = new DateTime($item['date']);
            $days = $playDate->diff($currentDate);
            $diffDate = $days->format("%a") + 1;
    ?>
            <div class="col-md-12 col-lg-4">
                <div class="customCard card shadow-sm col-12 h-100">
                    <div class="customCardBody card-body">
                        <div class="row gx-5">
                            <div class="col col-12">
                                <h5 class="card-title"><?php echo $item["description"] ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $item["companyName"] ?></h6>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo $item["date"] ?> | <?php echo $item["time"] ?> - <?php echo $item["endTime"] ?></h6>
                                <h6 class="card-subtitle mb-2"><?php echo $assignStatus ?></h6>
                            </div>
                            <div class="col col-12">
                                <div class="embed-responsive text-center col-12">
                                    <iframe class="col-12" src="https://maps.google.com/maps?q=<?php echo "" . $item["pCity"] . "+" . $item["pStreet"] . "+" . $item["pHouseNumber"] . "+" . $item["pPostalCode"] . "" ?>&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
                                </div>
                                <?php if (is_null($item["assigned"]) || $item["assigned"] == 3) { ?>
                                    <div class="row g-0">
                                        <button type="button" class="btn btn-success m-0 col-12" style="z-index: 10" data-bs-toggle="modal" data-bs-target="#participateModal<?php echo $item["requestId"] ?>"><?php if ($item["assigned"] == 3) { echo "Opnieuw aanmelden"; } else { echo "Aanmelden"; }; ?></button>
                                    </div>
                                <?php } else if ($item["assigned"] == 0) { ?>
                                    <div class="row g-0">
                                        <button type="button" class="btn btn-danger m-0 col-12" style="z-index: 10" data-bs-toggle="modal" data-bs-target="#deregisterNotAssignedModal<?php echo $item["requestId"]; ?>">Inschrijving annuleren</button>
                                    </div>
                                <?php } else if ($item["assigned"] == 1 && $diffDate > 1) { ?>
                                    <div class="row g-0">
                                        <button type="button" class="btn btn-danger m-0 col-12" style="z-index: 10" data-bs-toggle="modal" data-bs-target="#deregisterAssignedModal<?php echo $item["requestId"]; ?>">Inschrijving annuleren</button>
                                    </div>
                                <?php } else if ($item["assigned"] == 1 && $diffDate <= 1) { ?>
                                    <div class="row g-0">
                                        <button type="button" class="btn btn-danger m-0 col-12" style="z-index: 10" data-bs-toggle="modal" data-bs-target="#lateDeregisterAssignedModal<?php echo $item["requestId"]; ?>">Aanvraag voor afmelding</button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="customCardList list-group-item"><strong>Speellocatie: </strong> <?php echo $item["pStreet"] . " " . $item["pHouseNumber"] . ", " . $item["pCity"] ?></li>
                        <li class="customCardList list-group-item"><strong>Grimeerlocatie: </strong> <?php echo $item["gStreet"] . " " . $item["gHouseNumber"] . ", " . $item["gCity"] ?></li>
                    </ul>
                    <a class="stretched-link" style="z-index: 9" href="/opdracht/<?php echo $item["requestId"] ?>/details"></a>
                </div>
            </div>

            <!-- Modals -->
            <div class="modal fade" id="participateModal<?php echo $item["requestId"] ?>" tabindex="-1" aria-labelledby="participateModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="participateModalLabel">Weet u het zeker?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            Weet je zeker dat je je wilt aanmelden voor deze opdracht? Deze actie kan niet ongedaan worden.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Terug</button>
                            <a href="/opdracht/<?php echo $item["requestId"] ?>/aanmelden"><button type="button" class="btn btn-primary">Ga verder</button></a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deregisterNotAssignedModal<?php echo $item["requestId"]; ?>" tabindex="-1" aria-labelledby="deregisterNotAssignedModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deregisterNotAssignedModalLabel">Weet u het zeker?</h5>
                        </div>
                        <div class="modal-body">
                            Weet u zeker dat u zich wilt afmelden voor deze opdracht? Deze actie kan niet ongedaan worden.
                        </div>
                        <form action="/opdracht/afmelden" method="POST">
                            <div class="modal-footer">
                                <input type="hidden" name="requestId" value="<?php echo $item["requestId"] ?>">
                                <input type="button" class="cancelButton btn" data-bs-dismiss="modal" value="Anuleren">
                                <input type="submit" class="nextButton btn" value="Ga verder">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="deregisterAssignedModal<?php echo $item["requestId"]; ?>" tabindex="-1" aria-labelledby="deregisterAssignedModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deregisterAssignedModalLabel">Weet u het zeker?</h5>
                        </div>
                        <div class="modal-body">
                            Deze opdracht is al toegewezen aan u, gelieve u niet af te melden zonder geldige reden. <br>
                            Weet u zeker dat u zich wilt afmelden voor deze opdracht? Deze actie kan niet ongedaan worden.
                        </div>
                        <form action="/opdracht/afmelden" method="POST">
                            <div class="m-3">
                                <label for="message-text" class="col-form-label">Reden tot afmelding:</label>
                                <textarea class="form-control" name="reasonFor" id="message-text"></textarea>
                                <input type="hidden" name="requestId" value="<?php echo $item["requestId"] ?>">
                            </div>
                            <div class="modal-footer">
                                <input type="button" class="cancelButton btn" data-bs-dismiss="modal" value="Anuleren">
                                <input type="submit" class="nextButton btn" value="Ga verder">
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="lateDeregisterAssignedModal<?php echo $item["requestId"]; ?>" tabindex="-1" aria-labelledby="lateDeregisterAssignedModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="lateDeregisterAssignedModalLabel">Afmeldtermijn te kort</h5>
                        </div>
                        <div class="modal-body">
                            Deze opdracht vindt binnen 1 dag plaats. Neem contact op met de coördinator om u af te melden.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="cancelButton btn" data-bs-dismiss="modal">Oke</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php
        }
    } else {
        ?>
        <div class="container">

            <div class="row">
                <div class="col">
                    <div class="container-sm m-1 border shadow-sm rounded-3 w-auto">
                        <h2 class="formSectionTitle fw-bold m-3 text-center">Er zijn momenteel geen opdrachten waar u zich voor heeft ingeschreven!</h2>
                    </div>
                </div>
            </div>
        </div>

    <?php } ?>
</div>