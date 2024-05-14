<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Company;
use App\Entity\User;
use App\Form\Type\CompanyType;
use App\Handler\Company\CompanyHandlerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/company', name: 'app_company_')]
final class CompanyController extends AbstractController
{
    public function __construct(private readonly CompanyHandlerInterface $handler)
    {
    }

    #[Route('/list', name: 'list')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(#[CurrentUser] User $user): Response
    {
        $companies = $user->getCompanies();

        return $this->render('company/index.html.twig', [
            'companies' => $companies,
        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->handler->handlePersistInstance($company);

            return $this->redirectToRoute('app_company_list');
        }

        return $this->render('company/company.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Company $company): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->handler->handlePersistInstance($company);

            return $this->redirectToRoute('app_company_list');
        }

        return $this->render('company/company.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/view/{id}', name: 'view')]
    #[IsGranted(attribute: 'CAN_VIEW_COMPANY', subject: 'company')]
    public function viewDetails(Company $company): Response
    {
        return $this->render('company/view.html.twig', [
            'company' => $company,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    #[IsGranted(attribute: 'CAN_DELETE_COMPANY', subject: 'company')]
    public function deleteCompany(Company $company, Request $request): RedirectResponse
    {
        $token = $request->query->get('_token', '');

        if (!$this->isCsrfTokenValid('delete-company__'.$company->getId(), $token)) {
            throw new InvalidCsrfTokenException();
        }

        $this->handler->removeInstance($company);

        return $this->redirectToRoute('app_company_list');
    }
}
