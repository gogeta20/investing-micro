import type { IRepository } from "@/modules/home/domain/repositories/IRepository";
import { useMeToast } from "@/core/hooks/ToastStore";
import { ResponseBasic } from "@/modules/home/domain/models/ResponseData";
import { pokemon } from "@/modules/home/domain/models/Pokemon";


export class CreateItem {
  private repository: IRepository;

  constructor(symfonyRepository: IRepository) {
    this.repository = symfonyRepository;
  }
  async execute(formData: pokemon) {

    const meToast = useMeToast();
    try {
      this.repository.setEndPoint('/pokemon');
      const data = await this.repository.send(formData);
      meToast.addToast({ message: 'Ítem creado con éxito', type: 'success', duration: 5000 });
      return data;
    } catch (error) {
      console.error('Error fetching home data:', error);
      meToast.addToast({ message: 'error', type: 'error', duration: 4000 });

      throw error;
    }
  }
}
